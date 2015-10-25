<?php
class FgameUserModel extends Model{
	public function addGame($uid,$gid){
		$map['uid'] = $uid;
		$map['gid'] = $gid;
		if(!$this->where($map)->find()){
			$map['dateline'] = time();
			$this->add($map);
		}
	}
}