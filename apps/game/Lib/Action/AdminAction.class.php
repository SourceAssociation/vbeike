<?php
import('admin.Action.AdministratorAction');
class AdminAction extends AdministratorAction {
	public function index(){
	   $game_list = D('Game')->getGameList(0,0,true);
	   $this->assign($game_list);
	   $this->display();
	}
	
	public function setEnable(){
		$id = intval($_POST['id']);
		$map['fglev'] = intval($_POST['fglev']);
		$result = $_POST['fglev'] == 1?0:1;
		$gameDao = D('Game');
		$rs = $gameDao->where('fgid='.$id)->save($map);
		if($rs){
			echo $result;
		}else{
			echo -1;
		}
	}
}