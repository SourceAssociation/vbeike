<?php
	if (!defined('SITE_PATH')) exit(); 
	
	return array(  
    //  应用名称 [ 必填]  
    'NAME'        => '应用测试', 
    //  应用简介 [ 必填]  
    'DESCRIPTION'    => '开发应用测试',
	//  托管类型 [ 必填] （0:本地应用，1:远程应用）
	'HOST_TYPE'     => '0', 
	//  前台入口 [ 必填] （格式：Action/act） 
	'APP_ENTRY'     => 'Index/index', 
	//  应用图标 [ 必填]  
	'ICON_URL'     => SITE_URL   .   '/apps/test/Appinfo/ico_app.gif', 
	//  应用图标 [ 必填]  
	'LARGE_ICON_URL'   =>  SITE_URL   .   '/apps/test/Appinfo/ico_app_large.gif',  
	//  后台入口 [ 选填]  
	'ADMIN_ENTRY'   => 'Admin/index', 
	//  统计入口 [ 选填] （格式：Model/method） 
	'STATISTICS_ENTRY'   => 'TestStatistics/statistics',  
	//调试模式开启
	'DEBUG_MODE'=>true,
	);
?>