<?php
    /**
     * GiftAction
     * 礼物控制层
     *
     * @uses 
     * @package 
     * @version 
     * @copyright 2009-2011 SamPeng
     * @author SamPeng <sampeng87@gmail.com>
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
class IndexAction extends Action{
	private $fgTypeNames = array(1=>'动作', 2=>'冒险', 3=>'益智', 4=>'搞笑', 5=>'体育', 6=>'射击', 7 => '敏捷');
    public function index(){
        $game = D('Game', 'game');
    	if(!empty($_GET['ac']) && $_GET['ac'] == 'save' ){
    		$gid = isset($_GET['fgid'])?intval($_GET['fgid']):1;
    		$score = intval($_POST['gscore']);
    		$jumpUrl = U('game/Index/gameplay', array('gid'=>$gid));
    		if($score<=0){
    			$this->assign('jumpUrl',$jumpUrl);
    			$this->error("获得0分。提交积分失败!");
    		}else{
    			if(D('Fgscore', 'game')->addFgScore($gid,$this->mid,$score)){
	                $game = $game->where('fgid='.$gid)->find();
	                $this->doFeed($gid,$game['fgname'],$score);
                    $this->assign('jumpUrl',$jumpUrl);
                    $this->success("获得{$score}分。提交积分成功!");
    			} else {
    				redirect($jumpUrl, 0, '');
    			}
    		}
    	}

    	if(isset($_GET['who'])){
    		$gid = intval($_GET['gid']);
    		if ($_GET['who']=='me') {
    			$uid = $this->mid;
    		} else {
    			$fid = M('weibo_follow')->field('fid')->where("uid={$this->mid} AND type=0")->findAll();
    			$uid = getSubByKey($fid, 'fid');
    		}
    	}
    	$game_list = $game->getGameList($uid,$_GET['cid']);
    	$this->assign($game_list);
    	$this->assign('who',$_GET['who']);
    	$this->assign('type',$this->fgTypeNames);
    	$this->display();
    }
    
    public function gameplay(){
    	$game = D('Game', 'game');
    	$id = intval($_GET['gid']);
    	$game_info = $game->where('fgid='.$id)->find();
    	if(!$game_info){
    		$this->error('这个游戏不存在或已删除');
    		exit;
    	}
    	D('FgameUser', 'game')->addGame($this->mid,$id);
    	$score_dao = D('Fgscore', 'game');
    	$score = $score_dao->where('fgid='.$id)->order('score DESC')->limit(10)->findAll();
    	
    	$this->assign('score',$score);
    	$this->assign('game',$game_info);
    	$this->assign('who',$_GET['who']);
    	$this->assign('type',$this->fgTypeNames);
    	$this->display();
    }

    // 发布动态至微博
    public function doFeed($gid,$name,$score){
		// 微博
		$weibo_tpl_data = array('name'=>$name, 'score'=>$score, 'url'=>U('game/Index/gameplay',array('gid'=>$gid)));
		$weibo_tpl_data = model('Template')->parseTemplate('game_score_feed', array('body'=>$weibo_tpl_data));
		$weibo_data['content'] = $weibo_tpl_data['body'];
		D('Weibo','weibo')->doSaveWeibo($this->mid, $weibo_data, 0, '', '', '');
    }
}