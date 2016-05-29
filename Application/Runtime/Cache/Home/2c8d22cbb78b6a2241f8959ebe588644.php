<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>账号审核以及权限管理</title>
	<link rel="stylesheet" type="text/css" href="/Public/CSS/pagelist.css">
	<link rel="stylesheet" type="text/css" href="/Public/CSS/main.css">
    <link rel="stylesheet" type="text/css" href="/Public/CSS/table.css">
    <link rel="stylesheet" type="text/css" href="/Public/CSS/input.css">
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
                <p><?php echo session('username'); if (session('utype') == 1): ?>
                    同学
                <?php else : ?>
                    <?php if (session('utype') == 2): ?>
                        老师
                    <?php else : ?>
                        管理员
                    <?php endif ?>
                <?php endif ?></p>
                <a href='<?php echo U('Login/loginout');?>')>退出登录</a>
            </div>
        </div>
        <div class="menu">
            <ul class="menulist">
                <li>
                    <a href="<?php echo U('Index/index');?>">首页</a>
                </li>
                <li>
                    <a href="<?php echo U('Exam/listexam');?>">在线考试</a>
                </li>
                <li>
                    <a href="<?php echo U('Exam/listexam');?>">成绩查询</a>
                </li>
                <li>
                    <a href="<?php echo U('Problem/listproblem');?>">在线练习</a>
                </li>
                <li>
                    <a href="<?php echo U('Status/status');?>">状态查询</a>
                </li>
            </ul>
        </div>
        <div class="context">
            <div class="lab-full" style="margin-bottom:10px; font-weight:700;">
                账号审核以及权限管理
            </div>
            <div>
                <form name="registerForm" action="<?php echo U('administration');?>" method="get">
                    <div class="inputdiv">
                        Account: <input type="text" name="account">
                        Name:<input type="text" name="user_name">
                        Type:<input type="text" name="user_type" style="width:160px">
                        Status:<input type="text" name="user_status" style="width:160px">
                        <button type="submit" style="margin-right:20px; margin-top:11px;">提交</button>
                    </div>
                </form>
            </div>
			<table class="bordered">
				<thead>
					<tr>
						<th>id</th>
						<th>account</th>
						<th>username</th>
                        <th>usertype</th>
                        <th>status</th>
						<th>email</th>
						<th>phone</th>
						<th>lastlogintime</th>
                        <th>操作</th>
					</tr>
				</thead>
				<tbody id="dataBody">
					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr id="<?php echo ($vo["id"]); ?>">
							<td class="id"><?php echo ($vo["id"]); ?></td>
							<td class="userid"><?php echo ($vo["userid"]); ?></td>
							<td class="username"><?php echo ($vo["username"]); ?></td>
                            <td class="usertype"><?php echo ($vo["usertype"]); ?></td>
                            <td class="status"><?php echo ($vo["status"]); ?></td>
							<td><?php echo ($vo["email"]); ?></td>
							<td><?php echo ($vo["phone"]); ?></td>
							<td><?php echo ($vo["lastlogintime"]); ?></td>
                            <td><p class="up">权限提升</p><p class="down">权限降低</p><p class="change">状态更改</p></td>
						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>
		</div>
		<div class="pagelist"><?php echo ($page); ?></div>
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
                    <p>Designer & Developer : ZhuXiangdong</p>
                </li>
                <li>
                    <?php if (session('utype') == 3): ?>
                        <a href="">Administration</a>
                    <?php else: ?>
                        <p>Administration</p>
                    <?php endif ?></p>
                    
                </li>
            </ul>
        </div>
    </div>
</body>
<script src="/Public/JS/jquery.js"></script>
<script src="/Public/JS/admin.js"></script>
</html>