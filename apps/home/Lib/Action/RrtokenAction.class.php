<?php
include_once(dirname(dirname(__FILE__)).'/class/rr_config.inc.php');
include_once(dirname(dirname(__FILE__)).'/class/RenrenOAuthApiService.class.php');
include_once(dirname(dirname(__FILE__)).'/class/RenrenRestApiService.class.php');

//人人 API 类 封装了大部分 API函数，基于 Access_token 授权机制
class RenrenAPI{
	public $config;
	public $rrid;
	//组件初始化
	public function init(){}
	
	function __construct(){
		$user=M('user')->where('uid='.$this->mid)->find();
		$this->rrid = $user[rrid];
		global $rrapp_config;
		$this->config = $rrapp_config;
		$this->token_refresh();
		$this->restApi = new RenrenRestApiService;
		$this->oauthApi = new RenrenOAuthApiService;
	}
	function __destruct() {
    
	}
	
	function test(){
		echo $this->mid;
	}
	//获取授权口令
	function token_auth( $code ){
		$post_params = array('client_id'=>$this->config->APIKey,
			'client_secret'=>$this->config->SecretKey,
			'redirect_uri'=>$this->config->redirecturi,
			'grant_type'=>'authorization_code',
			'code'=>$code
		);
		$token_url='http://graph.renren.com/oauth/token';
		$access_info=$this->oauthApi->rr_post_curl($token_url,$post_params);//使用code换取token
		//print_r( $access_info ); Array ( [scope] => status_update publish_feed [expires_in] => 2594934 [refresh_token] => 195343|0.nKZ6X6L70LceZ6ofZWxhL2md9VCoC26u.287275261 [user] => Array ( [id] => 287275261 [name] => 陈威 [avatar] => Array ( [0] => Array ( [type] => avatar [url] => http://hdn.xnimg.cn/photos/hdn221/20120519/1545/h_head_BB5g_567c000012751375.jpg ) [1] => Array ( [type] => tiny [url] => http://hdn.xnimg.cn/photos/hdn521/20120519/1545/tiny_NLtX_7135c019117.jpg ) [2] => Array ( [type] => main [url] => http://hdn.xnimg.cn/photos/hdn221/20120519/1545/h_main_qpIP_567c000012751375.jpg ) [3] => Array ( [type] => large [url] => http://hdn.xnimg.cn/photos/hdn221/20120519/1545/h_large_62Y2_567c000012751375.jpg ) ) ) [access_token] => 
		//$access_info=$oauthApi->rr_post_fopen($token_url,$post_params);//如果你的环境无法支持curl函数，可以用基于fopen函数的该函数发送请求
		if( !isset($access_info['error']) ){
			$access_token=$access_info["access_token"];
			$expires_in=$access_info["expires_in"];
			$refresh_token=$access_info["refresh_token"];
			
			$model = RRToken::model()->findByAttributes(array('rrid'=> $access_info["user"]["id"] ));
			$this->rrid = $access_info["user"]["id"];
			if ( !$model ){ //首次授权
				$rrtoken = new RRToken;
				$rrtoken->attributes = array(
					'rrid'=>$access_info["user"]["id"],
					'access_token' => $access_info["access_token"],
					'expires_in' => $access_info["expires_in"],
					'refresh_token' => $access_info["refresh_token"],
					'createtime' => time(),
				);
				if( $rrtoken->save() ){
					return true;
				}
				
			}else{ //否则替换之,考虑用户可能取消授权又再授权
				$model->attributes = array(
					'access_token' => $access_info["access_token"],
					'expires_in' => $access_info["expires_in"],
					'refresh_token' => $access_info["refresh_token"],
					'createtime' => time(),
				);
				if($model->save()){
					return true;
				}
			}
		}else{
			return false;
		}
	}
	
	//刷新口令
	function token_refresh(){
		//刷新代码：
		//用refreshtoken刷新accesstoken（在同步新鲜事的时候取出对应网站用户的人人accesstoken，
		//如果用当前时间的秒数-expires_in>获取token的时间的秒数,则accesstoken过期，用refreshtoken刷新accesstoken）
		$model = RRToken::model()->findByAttributes(array('rrid'=> $this->rrid ));
		if( $model ){
			if( intval(time()) >  ( intval($model['expires_in']) + intval($model['createtime']) ) ){ //判断access_token 是否过期
				$post_params = array('client_id'=>$this->config->APIKey,
					'client_secret'=>$this->config->SecretKey,
					'refresh_token'=>$model['refresh_token'],
					'grant_type'=>'refresh_token'
				);
				$token_url='http://graph.renren.com/oauth/token';
				$access_info=$this->oauthApi->rr_post_curl($token_url,$post_params);//使用code换取token
				$model->attributes = array(
					'access_token' => $access_info["access_token"],
					'expires_in' => $access_info["expires_in"],
					'refresh_token' => $access_info["refresh_token"],
					'createtime' => time(),
				);
				if($model->save()){
				}
				$this->config->access_token = $access_info["access_token"];
			}else{ //还没过期 直接用
				$this->config->access_token = $model['access_token'];
				//echo 'OK';
			}
		}
	}
	//获取用户信息 需要查询的用户的ID，多个ID用逗号隔开。例如：uids=1232,342,12324。建议都传入此参数。当此参数为空时，缺省值为session_key或access_token对应用户的ID
	function user_info_get( $fields='uid,name,sex,birthday,mainurl,hometown_location,university_history,tinyurl,headurl',$uids = '' ){
		$this->token_refresh();
		if( $uids == '' ){
			$uids = $this->rrid;
		}
		$params = array('access_token'=>$this->config->access_token,'fields'=>$fields,'uids'=>$uids);
		return $this->restApi->rr_post_curl('users.getInfo',$params);
	}
	//获取好友 id
	function friends_get( $count = '9999' ){
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token,'count'=>$count);
		return $this->restApi->rr_post_curl('friends.get',$params);
	}
	
	//发布日志
	function blog_add( $title , $content ){
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token,'content'=>$content,'title'=>$title);
		return $this->restApi->rr_post_curl('blog.addBlog',$params);
	}
	
	//公共主页发布日志
	function page_blog_add( $title , $content, $page_id ){
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token,'content'=>$content,'title'=>$title,'page_id'=>$page_id);
		return $this->restApi->rr_post_curl('blog.addBlog',$params);
	}
	
	//发送新鲜事 
	function feed_publish( $name , $description, $url, $image='', $caption='', $action_name='', $action_link='', $message='' ){
		$this->token_refresh();
		$params = array(
			'access_token'=>$this->config->access_token,
			'method'=>'feed.publishFeed',
			'v'=>$this->config->APIVersion,
			'name'=>$name,
			'description'=>$description,
			'url'=>$url,
			'image'=>$image,
			'caption'=>$caption,
			'action_name'=>$action_name,
			'action_link'=>$action_link,
			'message'=>$message,
		);
		return $this->restApi->rr_post_curl('feed.publishFeed',$params);
	}
	
	//发送通知
	function notifications_send( $notification , $to_ids ){
		$this->token_refresh();
		$to_ids = explode(',',$to_ids);
		foreach( $to_ids as $id ):
			$params = array('access_token'=>$this->config->access_token,'to_ids'=>$id,'notification'=>$notification);
			$this->restApi->rr_post_curl('notifications.send',$params);
		endforeach;
	}
	
	//查询请求余数
	function allocation_get(){
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token);
		return $this->restApi->rr_post_curl('admin.getAllocation',$params);
	}
	
	//发布状态
	function status_update( $status ){	
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token,'status'=>$status);
		return $this->restApi->rr_post_curl('status.set',$params);
	}
	
	//公共主页发布状态
	function page_status_update( $status,$page_id ){
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token,'status'=>$status,'page_id'=>$page_id);
		return $this->restApi->rr_post_curl('status.set',$params);
	}
	
	//分享内容
	/*
	分享的类型：日志为1、照片为2、链接为6、相册为8、视频为10、音频为11、分享为20。
	当分享优酷视频、站外链接等人人网站外内容时，url为必须参数。此时type只能是：链接为6、视频为10、音频为11。
	*/
	function share( $type, $url, $comment=''){
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token,'type'=>$type,'url'=>$url,'comment'=>$comment);
		return $this->restApi->rr_post_curl('share.share',$params);
	}
	
	//分享站内内容
	function share_insite( $type, $ugc_id, $user_id, $comment=''){
		$this->token_refresh();
		$params = array('access_token'=>$this->config->access_token,'type'=>$type,'ugc_id'=>$ugc_id,'user_id'=>$user_id,'comment'=>$comment);
		return $this->restApi->rr_post_curl('share.share',$params);
	}
	
	//上传图片
	function photo_upload( $img_src, $caption='', $aid=0 ){	
		$this->token_refresh();
		preg_match('|\.(\w+)$|', $img_src, $ext);
		#转化成小写
		$ext = strtolower($ext[1]);

		$myfile=array('upload'=>array(
		'name'=>'iPic.'.$ext,
		'tmp_name'=>$img_src,//如果是服务器本地图片，可以这么写：'tmp_name'=>'c:/pic.jpg'
		'type'=>'image/'.$ext
		));

		$params = array('access_token'=>$this->config->access_token,'aid'=>$aid,'caption'=>$caption);
		//上传照片的方法，不默认开启，需要测试时，将如下两行代码解除注释，刷新一个该页面，即可将图片传到人人网
		return $this->restApi->rr_photo_post_fopen('photos.upload',$params,$myfile);//基于fopen函数的发送请求 
	}
	
	//公共主页上传图片
	function page_photo_upload( $img_src, $page_id, $caption='', $aid=0 ){
		$this->token_refresh();
		preg_match('|\.(\w+)$|', $img_src, $ext);
		#转化成小写
		$ext = strtolower($ext[1]);

		$myfile=array('upload'=>array(
		'name'=>'iPic.'.$ext,
		'tmp_name'=>$img_src,//如果是服务器本地图片，可以这么写：'tmp_name'=>'c:/pic.jpg'
		'type'=>'image/'.$ext
		));

		$params = array('access_token'=>$this->config->access_token,'aid'=>$aid,'caption'=>$caption,'page_id'=>$page_id);
		//上传照片的方法，不默认开启，需要测试时，将如下两行代码解除注释，刷新一个该页面，即可将图片传到人人网
		return $this->restApi->rr_photo_post_fopen('photos.upload',$params,$myfile);//基于fopen函数的发送请求 
	}

	//获取用户的相册列表信息。当取第一页时，会返回头像相册和快速上传相册。 此API需要用户授予 read_user_album 
	//uid	int	相册所有者的用户ID
	//page	int	分页的页数，默认值为1
	//count	int	分页后每页的个数，默认值为10，上限1000
	//aids	string	多个相册的ID，以逗号分隔，最多支持10个数据
	function album_get( $page=1, $count=10){
		$this->token_refresh();
		$params = array(
			'access_token'=>$this->config->access_token,
			'uid'=>$this->rrid,
			'page'=>$page,
			'count'=>$count,
		);
		return $this->restApi->rr_post_curl('photos.getAlbums',$params);
	}
	
	//获取头像相册
	function avatar_album_get(){
		$aid = '';
		$albums = $this->album_get();
		foreach( $albums as $album ){
			if( $album['name'] == '头像相册'){
				$aid = $album['aid'];
				break;
			}
		}
		$this->token_refresh();
		$params = array(
			'access_token'=>$this->config->access_token,
			'uid'=>$this->rrid,
			'aid'=>$aid,
		);
		return $this->restApi->rr_post_curl('photos.get',$params);
	}
	
}