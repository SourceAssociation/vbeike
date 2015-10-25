<?phpclass RenrenPublishAddons extends SimpleAddons{	var $pluginName = '人人同步发布';	var $info = '微博同步发布到人人新鲜事';	var $version = '0.01';	var $tsVersion = '2.8';	var $author = '白天（提供二次开发服务QQ519256369)';	var $site = 'http://t.thinksns.com/@bt';		function getHooksInfo(){		$this->apply('home_index_middle_publish','home_index_middle_publish');		$this->apply('weibo_publish_after','weibo_publish_after');	}		function weibo_publish_after(&$param){		if($u = M('renren_publish')->where('uid='.$this->mid.' AND is_active = 1')->find()){			$id = $param['weibo_id'];			$data = $param['post'];			$content = $data['content'];			if($type_info = unserialize($data['type_data'])){				if(isset($type_info[0])){					$temp = array_shift($type_info);					$type_data = $temp['picurl'];				}else{					$type_data = $type_info['picurl'];				}				if($data['type']==1){					if(isset($type_info['picurl'])){						$pic = UPLOAD_PATH.'/'.$type_info['picurl'];					}elseif(isset($type_info[0]['picurl'])){						$pic = UPLOAD_PATH.'/'.$type_info[0]['picurl'];					}				}				$pic = str_replace(SITE_PATH,SITE_URL,$pic);			}			require_once $this->path.'/config.inc.php';			require_once $this->path.'/HttpRequestService.class.php';			require_once $this->path.'/renrenRestApiService.class.php';			$refresh_uri = 'https://graph.renren.com/oauth/token?grant_type=refresh_token&refresh_token='.$u['refresh_token'].'&client_id='.$config->APIKey.'&client_secret='.$config->SecretKey;			$ch = curl_init();			curl_setopt($ch, CURLOPT_URL,$refresh_uri);			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);			$result = curl_exec($ch);			$res = json_decode($result);			$access_token = $res->access_token;			$refresh_token = $res->refresh_token;			$GLOBALS['config'] =& $config;			$rrObj = new RenrenRestApiService;			$params = array('name'=>date('H:i:s m-d'),							'description'=>$content,							'url'=>U('home/space/detail',array('id'=>$id)),							'image'=>"$pic",							'action_name'=>$GLOBALS['ts']['site']['site_name'],							'action_link'=>SITE_URL,							'message'=> '我在'.$GLOBALS['ts']['site']['site_name'].'发了一条微博',							'access_token'=>$access_token);//使用access_token调api的情况										$res = $rrObj->rr_post_curl('feed.publishFeed', $params);//curl函数发送请求			//dump($res);		}	}		function home_index_middle_publish(){		if($u = M('renren_publish')->where('uid='.$this->mid.' AND is_active = 1')->find())			$this->assign('is_bind',true);		$this->display('home_index_middle_publish');	}		function bind(){				if(M('renren_publish')->where('uid='.$this->mid)->find()){			header('location:/');			exit;		}else{			require_once $this->path.'/HttpRequestService.class.php';			require_once $this->path.'/config.inc.php';			require_once $this->path.'/renrenRestApiService.class.php';			$redirect_uri = Addons::createAddonUrl('RenrenPublish','bind');			$url = 'https://graph.renren.com/oauth/authorize?'.'client_id='.$config->APIKey.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=publish_feed';			if(!$_GET['code']){				echo '<dl class="pop_sync"><dt></dt>您还未绑定人人网帐号, 请点这里<dd><a class="btn_b" href="' . $url . '">开始绑定</a></dd></dl>';				exit;			}else{				$url = 'https://graph.renren.com/oauth/token?grant_type=authorization_code&client_id='.$config->APIKey.'&code='.$_GET['code'].'&client_secret='.$config->SecretKey.'&redirect_uri='.urlencode($redirect_uri);				 $ch = curl_init();				curl_setopt($ch, CURLOPT_URL,$url);				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);				$result = curl_exec($ch);				$res = json_decode($result);				$access_token = $res->access_token;				$refresh_token = $res->refresh_token;				$data['uid'] = $this->mid;				$data['access_token'] = $access_token;				$data['refresh_token'] = $refresh_token;				if($data['refresh_token'])					M('renren_publish')->add($data);				else					$this->error('绑定失败');				$this->success('绑定成功');			}		}		 	}		function unbind(){		M('renren_publish')->where('uid='.$this->mid)->delete();		exit('1');	}		function start(){		}		function install(){		$sql = "DROP TABLE IF EXISTS ".C('DB_PREFIX')."renren_publish";		M()->execute($sql);		$sql = "CREATE TABLE IF NOT EXISTS ".C('DB_PREFIX')."renren_publish ( uid int not null,																				access_token text,																				refresh_token text,																				is_active int default 1,																				PRIMARY KEY(uid)																				)ENGINE=MyISAM DEFAULT CHARSET=utf8";		if(false !== M()->execute($sql))			return true;	}		function uninstall(){		$sql = "DROP TABLE IF EXISTS ".C('DB_PREFIX')."renren_publish";		if(false !== M()->execute($sql))			return true;	}}