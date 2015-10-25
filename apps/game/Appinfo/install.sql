SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `ts_fgame_user`;
CREATE TABLE `ts_fgame_user` (
  `uid` int(11) NOT NULL,
  `gid` int(11) NOT NULL default '0',
  `dateline` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ts_fgamelist`;
CREATE TABLE `ts_fgamelist` (
  `fgid` int(11) NOT NULL auto_increment,
  `fgname` varchar(20) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `fglev` tinyint(4) NOT NULL,
  `fginstruction` varchar(400) NOT NULL,
  `reply_count` int(11) NOT NULL,
  `typeid` smallint(4) NOT NULL,
  PRIMARY KEY  (`fgid`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `ts_fgscore`;
CREATE TABLE `ts_fgscore` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `score` int(8) NOT NULL default '0',
  `gtime` int(10) NOT NULL default '0',
  `fgid` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fgid` (`fgid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `ts_fgamelist` VALUES ('1', '挖金子', 'wajin', '1', '看准金钩方向，下键挖矿，上键使用炸药', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('2', '药房故事', 'drug', '1', '上下方向键移动角色，左键取药方、送药，右键对应柜台抓药，病人队列到达药房门口时游戏结束', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('3', '打企鹅', 'daqie', '1', '点击鼠标左键，企鹅坠落，再点击鼠标左键击打企鹅，将企鹅打到空中去！当企鹅在空中时，可以对它进行操作，按 <- 键，逆时针旋转，按 -> 键，顺时针旋转', '0', '6');
INSERT INTO `ts_fgamelist` VALUES ('4', 'Star连连看', 'llk', '1', '鼠标点击2个相同的并且可以连接的图案后消去', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('5', '长跑总动员', 'cpzdy', '1', '这款游戏跟QQ音速游戏有点类似,相信大家因该会喜欢的.按空格是跳，左右方向键躲避障碍', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('6', '尖峰时刻', 'jfsk', '1', '鼠标左键控制飞机飞行的高度！~', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('7', '小龙打靶', 'xldb', '1', '考验你的反映与你的眼力！', '0', '6');
INSERT INTO `ts_fgamelist` VALUES ('8', '躲子弹', 'dzd', '1', '鼠标控制圆球的位置，躲避左侧飞来的子弹', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('9', '动物运动会', 'dwydh', '1', '动物运动会，用手中的红球来打这些可怜的小动物！', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('10', '完美停车', 'wmtc', '1', '很多朋友都有私家车吧！看看你会不会停车入位噢！', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('11', '网球颠球', 'wqdq', '1', '用鼠标点击网球，保证球不落地，颠球的次数越多分数越高', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('13', '叠叠乐', 'ddl', '1', '鼠标或者空格扔下，看谁罗的最高', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('14', '劲舞团', 'jwt', '1', '不用下载就可以玩劲舞啦！看看你的水平如何！', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('15', '射气球', 'sqq', '1', '控制美少女的高度射击气球，在怪物掉落之前将气球射没', '0', '6');
INSERT INTO `ts_fgamelist` VALUES ('16', 'NBA投篮大赛', 'nba', '1', '掌握好头球的力度和准确度，空格键是确认', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('17', '狂抽负心男友', 'kcfxny', '1', '玩法：按第一个按钮开始，在上边的游标走到中间格子里时迅速按下空格键掴他~如果在外面按，一下把他打倒在地就不好玩了。', '0', '4');
INSERT INTO `ts_fgamelist` VALUES ('18', '跑跑卡丁车', 'ppkdc', '1', '可爱的小MM也是赛车高手哦,来体验下MM马路杀手的感觉吧,MM开车的速度可是会越来越快哦，小心啊。键盘左右键控制方向，空格键跳起躲过障碍物。', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('19', '罗宾汉之箭', 'lbhzj', '1', '1、按住鼠标左键后，根据惯性当箭矢快到靶子的时候松开鼠标按键即可射箭。2、根据每箭的得分会从树上掉落苹果。3、您需要得到每关规定的分数后才可以进行下一关的游戏。 ', '0', '6');
INSERT INTO `ts_fgamelist` VALUES ('20', '台球', 'tq', '1', '鼠标左键单击“开始游戏”游戏开始，后用鼠标左键单击球桌固定白球位置，移动鼠标瞄准，按住鼠标左键并反方向拉动球杆调整力度，后松开左键击球，可按住空格键固定方向，亦可调整右下角击球点，任意出杆，没有击球顺序和局数限制，b键为“老板来了”键。', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('21', '黑方块', 'hfk', '1', '1、移动鼠标躲避红色方块 / 碰到黑色方块加分/ b键为“老板来了”键', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('22', '掘金勇士', 'jjys', '1', '可敲击两块以上相邻矿石 / 金块及特殊矿石有价值 / 过关时空地可得分/ b键为“老板来了”键\r\n', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('23', '虎口夺食', 'hkds', '1', '鼠标左键单击画面任一位置开始游戏，鼠标左键单击使猴子跳起接住老虎所扔出的果子，鼠标左键再次单击使上面的猴子接住下面猴子所抛出的果子', '0', '2');
INSERT INTO `ts_fgamelist` VALUES ('24', '翼龙骑士', 'ylqs', '1', '按空格键进行弹射起飞 / 在空中，按空格键扇动翅膀 / 在空中，按左（或A）右（或S）键进行翻转 / 顺时针翻转一圈可获得一个翅膀/ b键为“老板来了”键', '0', '2');
INSERT INTO `ts_fgamelist` VALUES ('25', '太阳神祖玛', 'tyszm', '1', '用鼠标控制射出的彩球 / 相同颜色的球达到三个获得积分/ b键为“老板来了”键', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('26', '钓鱼', 'dy', '1', '用鼠标控制左右，按下鱼钩钓上鱼来，千万不要钓到其他的东西哦，会扣分的', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('27', '大胖子跳水', 'dpzts', '1', '大胖在跳台上随时准备跳水，等到下面的跳靶移动来的时候按空格键，大胖就会跳下去。起跳过程中，命中其红色区域，则给予最高加分奖励。只要达到本关要求的分数就会进入下一关，关数越多跳靶的移动速度也会越来越快。', '0', '4');
INSERT INTO `ts_fgamelist` VALUES ('28', '拳击训练', 'qjxl', '1', '用鼠标控制左右，按下鱼钩钓上鱼来，千万不要钓到其他的东西哦，会扣分的', '0', '1');
INSERT INTO `ts_fgamelist` VALUES ('29', '弹跳超人', 'ttcr', '1', '这是一款跨越障碍的游戏，有没有想过将来有一天你也可以有超强的弹跳力，是不是很想体验一下飞跃的感觉了？跟随我们的主人公开始吧！操作：点击start开始游戏，1键垫高，2键跳跃，3键垫高跳跃。', '0', '1');
INSERT INTO `ts_fgamelist` VALUES ('30', '办公室接吻', 'bgsjw', '1', '这个女员工真是不得了，一面和老板打的火热，一面趁老板接电话的时候和男员工偷偷接吻，你能创造接吻的最高记录吗，千万不要被老板抓住阿，希望你得到美眉的香吻～游戏操作 鼠标左键点击。', '0', '4');
INSERT INTO `ts_fgamelist` VALUES ('31', '仙女下凡', 'xnxf', '1', '用鼠标控制仙女的左右方向', '0', '2');
INSERT INTO `ts_fgamelist` VALUES ('32', '恶搞跳绳', 'egts', '1', '鼠标左键控制起跳时间，中途会遇上上下左右环节', '0', '4');
INSERT INTO `ts_fgamelist` VALUES ('33', ' 超市大赢家', 'csdyj', '1', '在规定的时间内，将商品对应的放进滚动的地方', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('34', '小猴跳墩', 'xhtd', '1', '空格控制小猴跳起的时间', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('35', '八字胡顶足球', 'bzhdzq', '1', '八字胡男人，他也非常喜欢足球，不过没人和他踢，那就自己玩吧！好像也很其乐无穷的样子呢！先点play,再点Back,鼠标操作,你是否能突破吉尼斯记录？', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('36', '丛林小鸡', 'clxj', '1', '游戏介绍：可爱的丛林小鸡，超讨人喜欢。操作指南：使用方向键控制角色移动。空格键发射飞镖。吃到樱桃蛋糕后就可过关', '0', '6');
INSERT INTO `ts_fgamelist` VALUES ('37', '钓仙女', 'dxn', '1', '游戏非常可爱，很象黄金矿工游戏，只不过这一次是钓很可爱的小仙女。鼠标操作，鼠标左右移动来控制位置，左键点击钓起，在规定的时间要完成一定数量才可以哦，好玩，加油哦！', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('38', '接鸡蛋', 'jjd', '1', '帮助小刺猬尽可能多的接住鸡蛋，绿色鸡蛋不能接否则会减生命值，其他颜色的鸡蛋接了后能获得奖励。方向键控制，游戏载入后点击play，按↑开始。', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('39', '美女清洁工', 'mnqjg', '1', '玩法说明：键盘上下左右出招，垃圾从哪边出来，就按哪个方向，当出现从各个地方同时出现垃圾时，按空格键可使出绝招，加油哦。', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('40', '瀑布泡泡龙', 'pbppl', '1', '湍急的瀑布前面玩泡泡龙是不是很有趣呢？快来玩玩吧，加油哦。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('41', '钱夫人', 'qfr', '1', '贪钱的钱夫人，帮她挖宝去吧！她就可以每天躺在金币堆里纸醉金迷啦！鼠标操作，两个相同的翻开即可得到金币啦,其实相当于翻翻看啦，加油哦。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('42', '跷跷板', 'qqb', '1', '跷跷板跟随鼠移动，第一次点击鼠标后开始游戏，接住弹起的米欧和尼卡，尽量使他们跳得更高，获得的分数就越高，好玩，加油哦！', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('43', '生存英雄', 'scyx', '1', '方向键控制，躲避炸弹等各种危险，蓝色星星可以增加生命值。游戏载入后点击play、start开始，加油哦！', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('44', '太公钓鱼', 'tgdy', '1', '鱼嘴闭合的时候可将鱼钓入鱼篓中，注意钓起时不要抖动，不然鱼会掉下去；将鱼全部钓完会有额外奖励哦，好玩，加油哦', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('45', '贪吃蛇', 'tcs', '1', '玩法说明：键盘上下左右移动，吃掉金币和果子，不能撞墙，加油哦。', '0', '7');
INSERT INTO `ts_fgamelist` VALUES ('46', '小猴叠罗汉', 'xhdlh', '1', '可爱的小猴叠罗汉小游戏~不要让小猴子掉下去，三个同样颜色的小猴会变成一只大猴子，加油哦', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('48', '阻击窃贼', 'zjqz', '1', '游戏介绍：游戏中你要帮助附近的居民把门窗关好，不要让窃贼有机可乘。共有三次机会，只要坚持到警笛开启就可过关。操作说明：用鼠标拖动来关闭门窗，要随时注意门窗的开关情况。载入后，点击“PLAYNOW”按钮开始游戏', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('49', '醉汉走路', 'zhzl', '1', '玩法说明：鼠标左右移动控制醉汉平衡，不能摔倒，加油哦。', '0', '4');
INSERT INTO `ts_fgamelist` VALUES ('50', '躲猫猫', 'dmm', '1', '勇敢的天使挑战坏坏的企鹅，善良的天使不愿意伤害企鹅，所以只能逃跑。 鼠标控制天使逃跑。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('51', '水果对对碰', 'sgddp', '1', '鼠标点击操作，碰到三个或以上相同的就可以消掉，试下你能碰多少分哦。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('52', '钻石大盗', 'zsdd', '1', '一名大盗偷了价值连城的钻石首饰，我们要追捕他，可惜我们不是控制追捕的人而是控制逃跑的人。鼠标左右移动人物，点一下鼠标左键就可以跳起来，路上有障碍物要跳过去，不然被抓到了就会被扁得很惨喔。', '0', '1');
INSERT INTO `ts_fgamelist` VALUES ('53', '冰心公主', 'bxgz', '1', '点击公主，点住不放就会出现一条进度调，代表她跳起来的力度，松开鼠标月亮公主就会跳起来，她的倒影随之往下弹，这时用鼠标滑动可以控制倒影的位置，拿到更多的星星！～～～ 一次吃很多星星可以增加时间，这样会拿更多的分，加油哦。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('54', '天使超市', 'tscs', '1', '很有趣的游戏，天使MM的超市很特别，买东西不用付钱，只要你翻对两个一样的食品，就归你了哦！ 操作指南：点击下面按纽开始游戏，鼠标点点翻牌，相同的两张就可以消去，加油哦。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('55', '木工钉钉子', 'mgddz', '1', '很有趣的游戏，做为一个木工，钉钉子是经常要做的事情，空格键操作，看好下面的进度条，第一次的进度条是控制力度的，越往右力度越大，第二次是钉子上左右摇摆的进度条，要刚好跟钉子吻合的位置按下空格键敲下去！时间有限，钉歪了可以矫正哦，加油哦。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('56', '小猪打狐狸', 'xzdhl', '1', '哈哈，可怜的小狐狸要被小猪折磨了。看看小猪是否会棒下留情了。上下左右方向键控制移动，鼠标点击挥棒打。一定要算好时间差再挥棒，不然会被小狐狸踢到哦，加油哦。', '0', '6');
INSERT INTO `ts_fgamelist` VALUES ('57', '史努比射箭', 'snbsj', '1', '点击第一个按钮进入游戏，鼠标控制，上下移动鼠标调整方向，看准目标后，点击鼠标左键射箭，加油哦。', '0', '6');
INSERT INTO `ts_fgamelist` VALUES ('58', '外卖小子', 'wmxz', '1', '超级可爱的Q游戏，MM和GG是一家拉面店的外卖员，骑着小摩托送拉面上面，呵呵，画面精美，人物可爱！ 玩法：开始的时候选择MM或者GG，骑着小摩托上路，鼠标移动来控制方向，速度非常快，如果要减速就点鼠标左键或往边上开，要拿到拉面等食物，闹钟可以加时间，不要撞到路上的骑车、摩托、货物、积水等障碍物@！每关都有不同地图，试试你你能过几关吧！', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('59', '史努比冲浪', 'snbcl', '1', '超酷的冲浪运动，可爱的史努比来为你表演超难的特技动作！看台上好多人在鼓掌呢！方向键移动，按照绿色板上的动作用zxc分别变换不同的动作来完成表演。', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('60', '小鱼吃虫', 'xycc', '1', '鼠标点击控制鱼妈妈带着小鱼游动，避开敌人及障碍，吃到漂浮的虫子。游戏载入后点击play、continue开始游戏。', '0', '3');
INSERT INTO `ts_fgamelist` VALUES ('61', '砸鸡蛋', 'zjd', '1', '砸鸡蛋开始了，要快哦，鼠标操作，游戏结束后点击play again重新开始，加油吧！', '0', '4');
INSERT INTO `ts_fgamelist` VALUES ('62', '史努比冰球', 'snbbq', '1', '按鼠标左键来击球，将球打进门内将获得高分', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('63', '史努比拳击', 'snbqj', '1', '可爱史努比要试试打拳击，身手稳健的它拳击功夫如何呢？一切就由你来指挥决定吧！键盘数字键及空格键操作。根据画面出现的数字配合敲击数字键\"1、4、7与3、6、9\"操作。空格键防守。', '0', '5');
INSERT INTO `ts_fgamelist` VALUES ('64', '史努比滑翔', 'snbhx', '1', '可爱史努比做运动之空中滑翔！体验自由的滑翔之旅吧！键盘方向键控制操作，不要撞到乌鸦、黑云及跳伞的人！试下你能飞多久呢！', '0', '5');
