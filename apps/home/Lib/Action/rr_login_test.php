<?php
	//HttpClient.class.php
	public function rr_login(){   		//用人人登陆   授权---绑定邮箱---注册用户
	   Session::start();
	   include_once(dirname(dirname(__FILE__)).'/class/rr_config.inc.php');
	   //人人登录开始///////////////////////////////////////////////////////
	   if(isset($_GET['code'])){
			$code = t($_GET['code']);
			include_once(dirname(dirname(__FILE__)).'/class/RenrenOAuthApiService.class.php');
			include_once(dirname(dirname(__FILE__)).'/class/RenrenRestApiService.class.php');
			include_once(dirname(dirname(__FILE__)).'/class/HttpClient.class.php');
			if($code){
				$authApi=new RenrenOAuthApiService;
			 	$post_params=array('client_id'=>$config->APIKey,
						'client_secret'=>$config->SecretKey,
						'redirect_uri'=>$config->redirecturi,
						'grant_type'=>'authorization_code',
						'code'=>$code
				); 
				$token_url='http://graph.renren.com/oauth/token';
				$access_info=$authApi->rr_post_curl($token_url,$post_params);
				include_once(dirname(dirname(__FILE__)).'/class/RenrenOAuthApiService.class.php');
			include_once(dirname(dirname(__FILE__)).'/class/RenrenRestApiService.class.php');
			include_once(dirname(dirname(__FILE__)).'/class/HttpClient.class.php');
				$_SESSION['rr_code']=$code;
				$this->display();
			}   
				
				$token_url='http://graph.renren.com/oauth/token';
				$access_info=$authApi->rr_post_curl($token_url,$post_params);		//使用code换取token
				print_r($access_info); exit;
				$access_info=$oauthApi->rr_post_fopen($token_url,$post_params);//如果你的环境无法支持curl函数，可以用基于fopen函数的该函数发送请求
				if( !$access_info['error']){
					$rr_uid=$access_info["user"]["id"];
					$access_token=$access_info["access_token"];
					$expires_in=$access_info["expires_in"];
					$refresh_token=$access_info["refresh_token"];
							
					$rrtoken=model('Rrtoken');   //实例化一个rrtoken实例
					$data=$rrtoken->where('rr_uid='.$rr_uid)->find();
					if($data){    	 //说明之前是登陆过的用户
						$save['access_token']=$access_token;
						$save['expires_in']=$expires_in;
						$save['refresh_token']=$refresh_token;
						$save['time']=time();
						$rrtoken->where('uid='.$rr_uid)->data($save)->save();  	//更新数据库						
						$user = D('User', 'home')->getUserByIdentifier($rr_uid, 'uid');   //找到本地用人人id注册的用户
						service('Passport')->registerLogin($user,false,null);   //没有password  只能记录一个小时~
						
						redirect(U('home/User/index'));	
					}else{   //没有登陆过得用户
						$new['rr_uid']=$access_info['rr_uid'];
						$new['access_token']=$access_token;
						$new['refresh_token']=$refresh_token;
						$new['time']=time();
						$rrtoken->add($new);
						//让用户去绑定邮箱
						$user=$access_info["user"];
						redirect(U('home/public/rr_bind_email',array('params'=>$user)));
						// 注册   获取用户信息  让其注册进入  邮箱很重要 
						$pwd = mt_rand();	  	  // 生成一个随机密码存入数据库  发通知给用户
						$data['uid']	=   $rr_uid;
						$data['email']     = $user['email'];
						$data['password']  = md5($pwd);
						$data['uname']	   = t($user['name']);
						$data['ctime']	   = time();
						$data['register_ip']= get_client_ip();
						$data['login_ip']	 = get_client_ip();
						if (!($uid = D('User', 'home')->add($data)))   //存入数据库
							$this->error(L('reg_filed_retry'));
							Addons::hook('public_after_doregister',$uid);
						//将用户添加到myop_userlog，以使漫游应用能获取到用户信息
						$user_log = array(
							'uid'		=> $uid,
							'action'	=> 'add',
							'type'		=> '0',
							'dateline'	=> time(),
						);
						M('myop_userlog')->add($user_log);
						// 同步至UCenter
						if (UC_SYNC) {
							$uc_uid = uc_user_register($user['name'],$pwd,$user['email']);
							//echo uc_user_synlogin($uc_uid);
							if ($uc_uid > 0)
								ts_add_ucenter_user_ref($uid,$uc_uid,$data['uname']);
						}
						 //置为已登录, 供完善个人资料时使用
						service('Passport')->loginLocal($uid);    //false
						redirect(U('home/Public/userinfo'));	  
					}
				}else{    //验证失败
					$url = $config->app_base_url;
					echo "<script language='javascript' type='text/javascript'>"; 
					echo "window.location.href='$url'";
					echo "</script>";
				}
			}else{   
				$url=$config->app_base_url;
				echo "<script language='javascript' type='text/javascript'>"; 
				echo "window.location.href='$url'";
				echo "</script>";
			}
	   }
	   */
		//人人登录结束///////////////////////////////////////////////////////	 
		Session::pause();
}
?>