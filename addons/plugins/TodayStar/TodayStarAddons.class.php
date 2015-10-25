<?php
/**
 +------------------------------------------------------------------------------
 * RelatedUserAddons 可能认识的人插件
 * $data接受的参数:
 * array(
 * 	'uid'(可选)		=> $uid,	// 用户ID(默认当前用户)
 * 	'limit'(可选)	=> $limit,	// 展示的数量(默认为3x3=9个), 用户点击"关注"后自动补全
 * 	'max'(可选)		=> $max,	// 一次搜索获取的最大数结果数(默认50)
 * 	'title'(可选)	=> $title,	// 标题(默认"可能感兴趣的人")
 * )
 */
class TodayStarAddons extends SimpleAddons
{
	protected $version		= '1.0';
	protected $author		= '一麦网';
	protected $site			= 'http://www.yimai.cc';
	protected $info			= '今日之星';
	protected $pluginName	= '今日之星';
	protected $tsVersion	= "2.8"; // ts核心版本号

	public function getHooksInfo()
	{
		return $this->apply('home_index_right_toplist','todayStar');
	}
   
    public function todayStar(){
	  //首先算出今天的起止时间
	  $time['begin'] = mktime(0,  0,  0,  date('m'), date('d'), date('Y'));
	  $time['end']	 = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
      //查出原创微博统计
	  $db_prefix = C('DB_PREFIX');
	  $sql_weibo = "SELECT count(*) as weibo_count,uid FROM {$db_prefix}weibo WHERE `ctime` >= {$time['begin']} AND `ctime` <= {$time['end']} AND isdel=0 GROUP BY uid";
	  $res_weibo = M('')->query( $sql_weibo );
	  $stat_weibo = array();
	  foreach($res_weibo as $vw){
	     $stat_weibo[$vw['uid']] = $vw['weibo_count'];
	  }
      //查出微博评论统计
	  $sql_comment = "SELECT count(*) as comment_count,uid FROM {$db_prefix}weibo_comment WHERE `ctime` >= {$time['begin']} AND `ctime` <= {$time['end']} AND isdel=0 GROUP BY uid";
	  $res_comment = M('')->query($sql_comment);
      $stat_comment = array();
      foreach($res_comment as $vc){
	     $stat_comment[$vc['uid']] = $vc['comment_count'];
	  }
      //进行处理
	  $comment_uids = array_keys($stat_comment);
	  foreach($stat_weibo as $k =>$v){
	   if(in_array($k,$comment_uids)){
	      $stat_comment[$k]=$stat_comment[$k]+$v;
	   }else{
	      $stat_comment[$k] =$v;
	   }
	  }
	  arsort($stat_comment);
	  if(count($stat_comment)>5)
	    $list = array_slice($stat_comment,0,5,true);
      else
		$list = $stat_comment;
	  
	  foreach($list as $kl =>$vl){
		 if($vl<10){     //上榜最低限制10个（微博+评论）
			echo $v1;
		    unset($list[$kl]);
		  }
	  }
	  
	  $data = array();
	  $i=0;
	  foreach($list as $kk=>$vv){
	     $data[$i]['uid']=$kk;
		 $data[$i]['count'] = $vv;
		 $i++;
	  }
   
	  $this->assign('data',$data);
	  $this->display('todaystar');
	}

    public function start()
    {
        return true;
    }

	public function install()
	{
		
		return true;
	}

	public function uninstall()
	{
		return true;
	}
}