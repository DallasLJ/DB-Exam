<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="/Public/CSS/login.css">
	<style type="text/css">
		#login {
			height: 500px;
		    width: 400px;
		    margin: -280px 0 0 -200px;
		}
		#inputs input{
			background: #f1f1f1 url("/Public/Picture/edit.png") no-repeat 1% center ;
			background-size: auto 55%;
		}

		
	</style>
</head>
<body>
<div class="registerLayer">
	<form id="login" name="registerForm" action="/index.php/Home/Login/register.html" onsubmit="return validateForm()" method="post">
		<h1>Register</h1>
		<fieldset id="inputs">
			<input type="text" name="userid" placeholder="用户名">
			<input type="password" name="password" placeholder="密码">
			<input type="password" name="repassword" placeholder="再次输入密码">
			<input type="text" name="username" placeholder="名字">
			<input type="text" name="usertype" placeholder="用户类型：学生为1，老师为2">
			<input type="text" name="email" placeholder="邮件">
			<input type="text" name="phone" placeholder="手机">
		</fieldset>
		<fieldset id="actions">
			<button type="submit" id="submit">sign up</button>
		</fieldset>
	</form>
</div>
</body>
<script src="/Public/JS/register.js"></script>
<script src="/Public/JS/md5.js"></script>
</html>