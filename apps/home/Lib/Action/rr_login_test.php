<?php
	//HttpClient.class.php
	public function rr_login(){   		//�����˵�½   ��Ȩ---������---ע���û�
	   Session::start();
	   include_once(dirname(dirname(__FILE__)).'/class/rr_config.inc.php');
	   //���˵�¼��ʼ///////////////////////////////////////////////////////
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
				$access_info=$authApi->rr_post_curl($token_url,$post_params);		//ʹ��code��ȡtoken
				print_r($access_info); exit;
				$access_info=$oauthApi->rr_post_fopen($token_url,$post_params);//�����Ļ����޷�֧��curl�����������û���fopen�����ĸú�����������
				if( !$access_info['error']){
					$rr_uid=$access_info["user"]["id"];
					$access_token=$access_info["access_token"];
					$expires_in=$access_info["expires_in"];
					$refresh_token=$access_info["refresh_token"];
							
					$rrtoken=model('Rrtoken');   //ʵ����һ��rrtokenʵ��
					$data=$rrtoken->where('rr_uid='.$rr_uid)->find();
					if($data){    	 //˵��֮ǰ�ǵ�½�����û�
						$save['access_token']=$access_token;
						$save['expires_in']=$expires_in;
						$save['refresh_token']=$refresh_token;
						$save['time']=time();
						$rrtoken->where('uid='.$rr_uid)->data($save)->save();  	//�������ݿ�						
						$user = D('User', 'home')->getUserByIdentifier($rr_uid, 'uid');   //�ҵ�����������idע����û�
						service('Passport')->registerLogin($user,false,null);   //û��password  ֻ�ܼ�¼һ��Сʱ~
						
						redirect(U('home/User/index'));	
					}else{   //û�е�½�����û�
						$new['rr_uid']=$access_info['rr_uid'];
						$new['access_token']=$access_token;
						$new['refresh_token']=$refresh_token;
						$new['time']=time();
						$rrtoken->add($new);
						//���û�ȥ������
						$user=$access_info["user"];
						redirect(U('home/public/rr_bind_email',array('params'=>$user)));
						// ע��   ��ȡ�û���Ϣ  ����ע�����  �������Ҫ 
						$pwd = mt_rand();	  	  // ����һ���������������ݿ�  ��֪ͨ���û�
						$data['uid']	=   $rr_uid;
						$data['email']     = $user['email'];
						$data['password']  = md5($pwd);
						$data['uname']	   = t($user['name']);
						$data['ctime']	   = time();
						$data['register_ip']= get_client_ip();
						$data['login_ip']	 = get_client_ip();
						if (!($uid = D('User', 'home')->add($data)))   //�������ݿ�
							$this->error(L('reg_filed_retry'));
							Addons::hook('public_after_doregister',$uid);
						//���û���ӵ�myop_userlog����ʹ����Ӧ���ܻ�ȡ���û���Ϣ
						$user_log = array(
							'uid'		=> $uid,
							'action'	=> 'add',
							'type'		=> '0',
							'dateline'	=> time(),
						);
						M('myop_userlog')->add($user_log);
						// ͬ����UCenter
						if (UC_SYNC) {
							$uc_uid = uc_user_register($user['name'],$pwd,$user['email']);
							//echo uc_user_synlogin($uc_uid);
							if ($uc_uid > 0)
								ts_add_ucenter_user_ref($uid,$uc_uid,$data['uname']);
						}
						 //��Ϊ�ѵ�¼, �����Ƹ�������ʱʹ��
						service('Passport')->loginLocal($uid);    //false
						redirect(U('home/Public/userinfo'));	  
					}
				}else{    //��֤ʧ��
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
		//���˵�¼����///////////////////////////////////////////////////////	 
		Session::pause();
}
?>