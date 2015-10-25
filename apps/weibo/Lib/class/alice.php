<?php
require_once("./Snoopy.class.php");
function talk_with_alice($say){
	set_time_limit(0);
	$snoopy=new Snoopy();
	$url ='http://www.pandorabots.com/pandora/talk?botid=f5d922d97e345aa1&skin=custom_input';
	$data['botcust2'] = 'b8f8161adbc52ff9';
	$data['input'] = $say;
	$snoopy->submit($url,$data);
	$res = $snoopy->results;
	preg_match('(<b>A\.L\.I\.C\.E\.:</b>(.*))',$res,$match);
	return $match[1];
}
print talk_with_alice($_POST['say']);