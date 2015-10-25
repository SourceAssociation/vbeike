<?php
	$config = new stdClass;
	$config->APIURL		= 'http://api.renren.com/restserver.do'; //RenRen网的API调用地址，不需要修改
	$config->APPID		= '210688';	//你的API Key，请自行申请
	$config->APIKey		=  'e37547410e8947619dffd409ee7c734e'; //你的API Key，请自行申请
	$config->SecretKey	=  'a97f229ea820476b8c19eeb080866578';	//你的API 密钥
	$config->APIVersion	= '1.0';	//当前API的版本号，不需要修改
	$config->decodeFormat	= 'json';	//默认的返回格式，根据实际情况修改，支持：json,xml
	$config->app_base_url = 'http://www.wbeike.com/Vbeike/'; //应用所在服务器的基本目录 
	$config->redirecturi= U('home/public/rr_login');//授权成功之后的跳转地址
	$config->scope='publish_feed,photo_upload,publish_blog,status_update,publish_share,send_invitation'; //admin_page,
?>