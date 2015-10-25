<?php
class FgscoreModel extends Model{
	public function addFgScore($gid,$uid,$score){
		$map['fgid'] = $gid;
		$map['uid'] = $uid;
		$old_score = $this->where($map)->find();
		if(!$old_score){
			$map['gtime'] = time();
			$map['score'] = $score;
			$this->add($map);
			return true;
		}
		
		if($score<=$old_score['score']){
			return false;
		}else{
			
			$save['score']=$score;
			$save['gtime']=time();
			
			$this->where($map)->save($save);
			return true;
		}
	}
}