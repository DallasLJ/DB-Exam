<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>AddExam</title>
    <link rel="stylesheet" type="text/css" href="/Public/CSS/main.css">
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
            <div class="lab" style="margin-bottom:10px; font-weight:700;">添加考试</div>
            <form name="registerForm" action="/index.php/Home/Exam/add.html" onsubmit="return validateForm()" method="post">
                <div class="inputdiv">
                    Examname: <input type="text" name="exam_name">
                    <input type="text" name="teacher_id" value=<?php echo ($teacher); ?> style="display:none;">
                    StartTime: <input type="text" name="start_time">
                    EndTime:<input type="text" name="end_time">
                    <button type="submit" style="margin-right:20px; margin-top:11px;">sign up</button>
                </div>
            </form>
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
                    <?php if (session('utype') == 3): ?>
                        <a href="<?php echo U('Admin/administration');?>">Administration</a>
                    <?php else: ?>
                        <p>Administration</p>
                    <?php endif ?></p>
                </li>
            </ul>
        </div>
    </div>
</body>
<script src="/Public/JS/register.js"></script>
<script src="/Public/JS/md5.js"></script>
</html>