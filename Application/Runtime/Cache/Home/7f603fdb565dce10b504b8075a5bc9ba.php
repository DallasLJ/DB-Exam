<?php if (!defined('THINK_PATH')) exit();?><<!DOCTYPE html>
<html>
<head>
	<title>test volist</title>
</head>
<body>
<ul class="hello">
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
			<p><?php echo ($vo["id"]); ?></p>
			<p><?php echo ($vo["username"]); ?></p>
		</li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
</body>
</html>