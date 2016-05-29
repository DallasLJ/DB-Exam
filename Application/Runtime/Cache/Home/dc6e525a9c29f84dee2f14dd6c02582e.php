<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>Problem-<?php echo ($data['problem_order']); ?></title>
	<link rel="stylesheet" type="text/css" href="/Public/CSS/main.css">
    <link rel="stylesheet" type="text/css" href="/Public/CSS/problem.css">
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
			<h1><?php echo ($data['problem_order']); ?>.<?php echo ($data['title']); ?></h1>
			<div class="lab">题目描述</div>
			<div class="description"><?php echo ($data['content']); ?></div>
			<div class="lab">题型</div>
			<div class="description"><?php echo ($data['type']); ?></div>
			<div class="lab">最大提交次数</div>
			<div class="description"><?php echo ($data['submit_times']); ?></div>
            <div class="lab">剩余时间</div>
            <div class="description">
                <span id="t_d">00天</span>
                <span id="t_h">00时</span>
                <span id="t_m">00分</span>
                <span id="t_s">00秒</span>
            </div>
			<div class="lab">你的答案</div>
			<div>
				<form name="submitForm" id="submitprb" action="<?php echo ($submiturl); ?>" method="post">
					<input name="examid" value="<?php echo ($data['exam_id']); ?>" style="display:none">
					<input name="epid" value="<?php echo ($data['id']); ?>" style="display:none">
                    <input name="problem_order" value="<?php echo ($data['problem_order']); ?>" style="display:none">
					<textarea rows="20" cols="20" name="answer"></textarea>
					<button type="submit">提交答案</button>
				</form>
			</div>
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
<script type="text/javascript">
    var starttime = '<?php echo ($exam['start_time']); ?>';
    var endtime = '<?php echo ($exam['end_time']); ?>';
</script>
<script type="text/javascript" src="/Public/JS/time.js"></script>
</html>