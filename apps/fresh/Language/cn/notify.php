<?php 
return  array(
	'qback'   => array(
		'title' => $actor.'回答了你在北科问问上的问题',
		'body'  =>$sendback.'<br /> <div class="quote"><p><span class="quoteR">'.$content.'</span></p></div><br /><a href="'.U('fresh/Index/question').'" target="_blank">去看看</a>',
	),
);
?>