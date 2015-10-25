<?php
/**
 * ShareOtherAddons 
 * 分享到外站的插件
 * @uses SimpleExample
 * @package 
 * @version $id$
 * @copyright 2001-2013 SamPeng 
 * @author SamPeng <penglingjun@zhishisoft.com> 
 * @license PHP Version 5.2 {@link www.sampeng.org}
 */
class ShareOtherAddons extends SimpleAddons
{
    protected $version    = '1.0';			                     // 版本号
    protected $author     = "SamPeng";			                 // 作者
    protected $site       = "http://www.sampeng.org";		     // 网站
    protected $info       = "站内分享到站外";			     // 描述信息
    protected $pluginName = "分享插件";		                     // 插件的中文/英文名字
    protected $tsVersion  = "2.5";                               // ts核心版本号

    public function getHooksInfo()
    {
        $this->apply('weibo_bottom_middle','shareBaidu');
        $this->apply('blog_share','shareBaidu');
        $this->apply('group_bbs_share','shareBaidu');
        $this->apply('album_share','shareBaidu');
        $this->apply('photo_share','shareBaidu');
        $this->apply('poster_share','shareBaidu');
        $this->apply('vote_share','shareBaidu');
        $this->apply('event_share','shareBaidu');
    }
    public function shareBaidu($param)
    {
        $this->assign('weibo',$param);
        $this->display('baidu');
    }
}
