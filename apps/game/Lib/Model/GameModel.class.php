<?php
class GameModel extends Model{
	protected  $tableName="fgamelist";
	public function getGameList($uid=0,$cid=0,$state=false){
		if($uid != 0){
			$map1['uid'] = array('in',$uid);
            $gid_list = D('FgameUser', 'game')->where($map1)->findAll();
            	foreach($gid_list as $value){
            		$gid_list_id[] = $value['gid'];
            	}
			$map['fgid'] = array('in',$gid_list_id);
		}
		$state==false && $map['fglev'] = 1;
		if($cid ==0){
			return $this->where($map)->findPage(30);
		}else{
			$map['typeid']=intval($cid);
			return $this->where($map)->findPage();
		}
	}
}