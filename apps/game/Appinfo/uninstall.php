<?php
if (!defined('SITE_PATH')) exit();

$db_prefix = C('DB_PREFIX');

$sql = array(
	// game数据
	"DROP TABLE IF EXISTS `{$db_prefix}fgame_user`;",
	"DROP TABLE IF EXISTS `{$db_prefix}fgamelist;",
	"DROP TABLE IF EXISTS `{$db_prefix}fgscore;",
	// ts_system_data数据
	"DELETE FROM `{$db_prefix}system_data` WHERE `list` = 'game'",
	// 模板数据
	"DELETE FROM `{$db_prefix}template` WHERE `type` = 'game';",
	// 积分规则
	"DELETE FROM `{$db_prefix}credit_setting` WHERE `type` = 'game';",
);

foreach ($sql as $v)
	M('')->execute($v);