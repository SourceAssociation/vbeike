<?php
	//获取应用配置参数
function getConfig($key=NULL){
	$config = model('Xdata')->lget('gift');
	$config['credit'] || $config['credit']='score';
	if($key==NULL){
		return $config;
	}else{
		return $config[$key];	
	}
}

   function qbelong($qid){	// 判断一个问题是否属于当前用户 
		$db=M('question');
		$q=$db->where('qid='.$qid)->find();
		if($q['quid']==$mid){
			return 1;
		}else{
			return 0;
		}
   }
   
   function getQuserId($qid){
		$db=M('question');
		$q=$db->where('qid='.$qid)->find();
		if($q){
			return $q['quid'];
		}else 
			return false;
   }
?>