<?php
// vim: foldmethod=marker

/* Generic exception class
 */
class doubanOAuthException extends Exception {/*{{{*/
  // pass
}/*}}}*/

class doubanOAuthConsumer {/*{{{*/
  public $key;
  public $secret;

  function __construct($key, $secret, $callback_url=NULL) {/*{{{*/
    $this->key = $key;
    $this->secret = $secret;
    $this->callback_url = $callback_url;
  }/*}}}*/

  function __toString() {/*{{{*/
    return "OAuthConsumer[key=$this->key,secret=$this->secret]";
  }/*}}}*/
}/*}}}*/

class doubanOAuthToken {/*{{{*/
  // access tokens and request tokens
  public $key;
  public $secret;

  /**
   * key = the token
   * secret = the token secret
   */
  function __construct($key, $secret) {/*{{{*/
    $this->key = $key;
    $this->secret = $secret;
  }/*}}}*/

  /**
   * generates the basic string serialization of a token that a server
   * would respond to request_token and access_token calls with
   */
  function to_string() {/*{{{*/
    return "oauth_token=" . doubanOAuthUtil::urlencode_rfc3986($this->key) .
        "&oauth_token_secret=" . doubanOAuthUtil::urlencode_rfc3986($this->secret);
  }/*}}}*/

  function __toString() {/*{{{*/
    return $this->to_string();
  }/*}}}*/
}/*}}}*/

class doubanOAuthSignatureMethod {/*{{{*/
  public function check_signature(&$request, $consumer, $token, $signature) {
    $built = $this->build_signature($request, $consumer, $token);
    return $built == $signature;
  }
}/*}}}*/

class doubanOAuthSignatureMethod_HMAC_SHA1 extends doubanOAuthSignatureMethod {/*{{{*/
  function get_name() {/*{{{*/
    return "HMAC-SHA1";
  }/*}}}*/

  public function build_signature($request, $consumer, $token) {/*{{{*/
    $base_string = $request->get_signature_base_string();
    $request->base_string = $base_string;

    $key_parts = array(
      $consumer->secret,
      ($token) ? $token->secret : ""
    );

    $key_parts = doubanOAuthUtil::urlencode_rfc3986($key_parts);
    $key = implode('&', $key_parts);

    return base64_encode( hash_hmac('sha1', $base_string, $key, true));
  }/*}}}*/
}/*}}}*/

class doubanOAuthSignatureMethod_PLAINTEXT extends doubanOAuthSignatureMethod {/*{{{*/
  public function get_name() {/*{{{*/
    return "PLAINTEXT";
  }/*}}}*/

  public function build_signature($request, $consumer, $token) {/*{{{*/
    $sig = array(
      doubanOAuthUtil::urlencode_rfc3986($consumer->secret)
    );

    if ($token) {
      array_push($sig, doubanOAuthUtil::urlencode_rfc3986($token->secret));
    } else {
      array_push($sig, '');
    }

    $raw = implode("&", $sig);
    // for debug purposes
    $request->base_string = $raw;

    return doubanOAuthUtil::urlencode_rfc3986($raw);
  }/*}}}*/
}/*}}}*/

class doubanOAuthSignatureMethod_RSA_SHA1 extends doubanOAuthSignatureMethod {/*{{{*/
  public function get_name() {/*{{{*/
    return "RSA-SHA1";
  }/*}}}*/

  protected function fetch_public_cert(&$request) {/*{{{*/
    // not implemented yet, ideas are:
    // (1) do a lookup in a table of trusted certs keyed off of consumer
    // (2) fetch via http using a url provided by the requester
    // (3) some sort of specific discovery code based on request
    //
    // either way should return a string representation of the certificate
    throw Exception("fetch_public_cert not implemented");
  }/*}}}*/

  protected function fetch_private_cert(&$request) {/*{{{*/
    // not implemented yet, ideas are:
    // (1) do a lookup in a table of trusted certs keyed off of consumer
    //
    // either way should return a string representation of the certificate
    throw Exception("fetch_private_cert not implemented");
  }/*}}}*/

  public function build_signature(&$request, $consumer, $token) {/*{{{*/
    $base_string = $request->get_signature_base_string();
    $request->base_string = $base_string;

    // Fetch the private key cert based on the request
    $cert = $this->fetch_private_cert($request);

    // Pull the private key ID from the certificate
    $privatekeyid = openssl_get_privatekey($cert);

    // Sign using the key
    $ok = openssl_sign($base_string, $signature, $privatekeyid);

    // Release the key resource
    openssl_free_key($privatekeyid);

    return base64_encode($signature);
  } /*}}}*/

  public function check_signature(&$request, $consumer, $token, $signature) {/*{{{*/
    $decoded_sig = base64_decode($signature);

    $base_string = $request->get_signature_base_string();

    // Fetch the public key cert based on the request
    $cert = $this->fetch_public_cert($request);

    // Pull the public key ID from the certificate
    $publickeyid = openssl_get_publickey($cert);

    // Check the computed signature against the one passed in the query
    $ok = openssl_verify($base_string, $decoded_sig, $publickeyid);

    // Release the key resource
    openssl_free_key($publickeyid);

    return $ok == 1;
  } /*}}}*/
}/*}}}*/

class doubanOAuthRequest {/*{{{*/
  private $parameters;
  private $http_method;
  private $http_url;
  // for debug purposes
  public $base_string;
  public static $version = '1.0';

  function __construct($http_method, $http_url, $parameters=NULL) {/*{{{*/
    @$parameters or $parameters = array();
    $this->parameters = $parameters;
    $this->http_method = $http_method;
    $this->http_url = $http_url;
  }/*}}}*/


  /**
   * attempt to build up a request from what was passed to the server
   */
  public static function from_request($http_method=NULL, $http_url=NULL, $parameters=NULL) {/*{{{*/
    $scheme = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http' : 'https';
    @$http_url or $http_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
    @$http_method or $http_method = $_SERVER['REQUEST_METHOD'];

    $request_headers = doubanOAuthRequest::get_headers();

    // let the library user override things however they'd like, if they know
    // which parameters to use then go for it, for example XMLRPC might want to
    // do this
    if ($parameters) {
      $req = new doubanOAuthRequest($http_method, $http_url, $parameters);
    } else {
      // collect request parameters from query string (GET) and post-data (POST) if appropriate (note: POST vars have priority)
      $req_parameters = $_GET;
      if ($http_method == "POST" && @strstr($request_headers["Content-Type"], "application/x-www-form-urlencoded") ) {
        $req_parameters = array_merge($req_parameters, $_POST);
      }

      // next check for the auth header, we need to do some extra stuff
      // if that is the case, namely suck in the parameters from GET or POST
      // so that we can include them in the signature
      if (@substr($request_headers['Authorization'], 0, 6) == "OAuth ") {
        $header_parameters = doubanOAuthRequest::split_header($request_headers['Authorization']);
        $parameters = array_merge($req_parameters, $header_parameters);
        $req = new doubanOAuthRequest($http_method, $http_url, $parameters);
      } else $req = new doubanOAuthRequest($http_method, $http_url, $req_parameters);
    }

    return $req;
  }/*}}}*/

  /**
   * pretty much a helper function to set up the request
   */
  public static function from_consumer_and_token($consumer, $token, $http_method, $http_url, $parameters=NULL) {/*{{{*/
    @$parameters or $parameters = array();
    $defaults = array("oauth_version" => doubanOAuthRequest::$version,
                      "oauth_nonce" => doubanOAuthRequest::generate_nonce(),
                      "oauth_timestamp" => doubanOAuthRequest::generate_timestamp(),
                      "oauth_consumer_key" => $consumer->key);
    $parameters = array_merge($defaults, $parameters);

    if ($token) {
      $parameters['oauth_token'] = $token->key;
    }
    return new doubanOAuthRequest($http_method, $http_url, $parameters);
  }/*}}}*/

  public function set_parameter($name, $value) {/*{{{*/
    $this->parameters[$name] = $value;
  }/*}}}*/

  public function get_parameter($name) {/*{{{*/
    return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
  }/*}}}*/

  public function get_parameters() {/*{{{*/
    return $this->parameters;
  }/*}}}*/

  /**
   * Returns the normalized parameters of the request
   *
   * This will be all (except oauth_signature) parameters,
   * sorted first by key, and if duplicate keys, then by
   * value.
   *
   * The returned string will be all the key=value pairs
   * concated by &.
   *
   * @return string
   */
  public function get_signable_parameters() {/*{{{*/
    // Grab all parameters
    $params = $this->parameters;

    // Remove oauth_signature if present
    if (isset($params['oauth_signature'])) {
      unset($params['oauth_signature']);
    }

    // Urlencode both keys and values
    $keys = doubanOAuthUtil::urlencode_rfc3986(array_keys($params));
    $values = doubanOAuthUtil::urlencode_rfc3986(array_values($params));
    $params = array_combine($keys, $values);

    // Sort by keys (natsort)
    uksort($params, 'strcmp');

    // Generate key=value pairs
    $pairs = array();
    foreach ($params as $key=>$value ) {
      if (is_array($value)) {
        // If the value is an array, it's because there are multiple
        // with the same key, sort them, then add all the pairs
        natsort($value);
        foreach ($value as $v2) {
          $pairs[] = $key . '=' . $v2;
        }
      } else {
        $pairs[] = $key . '=' . $value;
      }
    }

    // Return the pairs, concated with &
    return implode('&', $pairs);
  }/*}}}*/

  /**
   * Returns the base string of this request
   *
   * The base string defined as the method, the url
   * and the parameters (normalized), each urlencoded
   * and the concated with &.
   */
  public function get_signature_base_string() {/*{{{*/
    $parts = array(
      $this->get_normalized_http_method(),
      $this->get_normalized_http_url(),
      $this->get_signable_parameters()
    );

    $parts = doubanOAuthUtil::urlencode_rfc3986($parts);

    return implode('&', $parts);
  }/*}}}*/

  /**
   * just uppercases the http method
   */
  public function get_normalized_http_method() {/*{{{*/
    return strtoupper($this->http_method);
  }/*}}}*/

  /**
   * parses the url and rebuilds it to be
   * scheme://host/path
   */
  public function get_normalized_http_url() {/*{{{*/
    $parts = parse_url($this->http_url);

    $port = @$parts['port'];
    $scheme = $parts['scheme'];
    $host = $parts['host'];
    $path = @$parts['path'];

    $port or $port = ($scheme == 'https') ? '443' : '80';

    if (($scheme == 'https' && $port != '443')
        || ($scheme == 'http' && $port != '80')) {
      $host = "$host:$port";
    }
    return "$scheme://$host$path";
  }/*}}}*/

  /**
   * builds a url usable for a GET request
   */
  public function to_url() {/*{{{*/
    $out = $this->get_normalized_http_url() . "?";
    $out .= $this->to_postdata();
    return $out;
  }/*}}}*/

  /**
   * builds the data one would send in a POST request
   *
   * TODO(morten.fangel):
   * this function might be easily replaced with http_build_query()
   * and corrections for rfc3986 compatibility.. but not sure
   */
  public function to_postdata() {/*{{{*/
    $total = array();
    foreach ($this->parameters as $k => $v) {
      if (is_array($v)) {
        foreach 