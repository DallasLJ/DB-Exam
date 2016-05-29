<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>index</title>
	<link rel="stylesheet" type="text/css" href="/Public/CSS/main.css">
</head>
<body>
	<div class="top"></div>
	<div class="main">
		<div class="pic">
			<div id="logo"><img src="/Public/Picture/Photoshop.png" style="height:100px;weight:100px;"></div>
			<div id="title">
				<h1>在线考试系统</h1>
				<h2>快乐考试，考试快乐</h2>
			</div>
			<div style="float:right;width:100px;height:100px;">
				<p><?php echo session('username');?>老师</p>
				<a href='<?php echo U('Login/loginout');?>')>退出登录</a>
			</div>
		</div>
		<div class="menu">
			<ul class="menulist">
				<li class="border-right-white">
					<a href="<?php echo U('Index/index');?>">首页</a>
				</li>
				<li class="border-right-white">
					<a href="<?php echo U('Exam/listexam');?>">在线考试</a>
				</li>
				<li class="border-right-white">
					<a href="<?php echo U('Exam/listexam');?>">成绩查询</a>
				</li>
				<li class="border-right-white">
					<a href="<?php echo U('Problem/listproblem');?>">在线练习</a>
				</li>
				<li class="border-right-blue">
					<a href="<?php echo U('Status/status');?>">状态查询</a>
				</li>
			</ul>
		</div>
		<div class="context">
			<p>欢迎来到考试系统</p>
		</div>
		<div class="footer">
			<ul class="footer_list">
				<li class="footer_href">
					<a href="<?php echo U('Index/index');?>">Home</a>
					|
					<a href="#top">Top</a>
				</li>
				<li class="footer_server">
					<p>Online Examination System 1.0</p>
					<p>Copyright © 2005-2016. All Rights Reserved.</p>
					<p>Designer & Developer : Zhuxiangdong</p>
				</li>
				<li>
					<p>Administration</p>
				</li>
			</ul>
		</div>
	</div>
</body>
</html>