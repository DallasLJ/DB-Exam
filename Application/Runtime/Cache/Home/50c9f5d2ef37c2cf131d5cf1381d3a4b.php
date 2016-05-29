<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="/Public/CSS/login.css">
</head>
<body>

<form id="login" name="loginForm" action="/index.php/Home/Login/login.html" onsubmit="transformPassword()" method="post">
	<h1>LogIn</h1>
	<fieldset id="inputs">
		<input id="username" type="text" name="userid" placeholder="Username">
		<input id="password" type="password" name="password" placeholder="Password">
	</fieldset>
	<fieldset id="actions">
		<button type="submit" id="submit">login</button>
		<a href='<?php echo U('Login/register');?>'>注册新账号</a>
	</fieldset>
</form>

</body>
<script src="/Public/JS/login.js"></script>
<script src="/Public/JS/md5.js"></script>
</html>