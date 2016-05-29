<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>Exam List</title>
	<link rel="stylesheet" type="text/css" href="/Public/CSS/table.css">
	<link rel="stylesheet" type="text/css" href="/Public/CSS/pagelist.css">
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
            <?php if (session('utype') >= 2): ?>
                <div class="lab" style="margin-bottom:10px; font-weight:700;">添加考试</div>
                <form name="registerForm" action="<?php echo U('add');?>" onsubmit="return validateForm()" method="post">
                    <div class="inputdiv">
                        Examname: <input type="text" name="exam_name">
                        <input type="text" name="teacher_id" style="display:none;" value=<?php echo ($teacher); ?>>
                        StartTime: <input type="text" name="start_time">
                        EndTime:<input type="text" name="end_time">
                        <button type="submit" style="margin-right:20px; margin-top:11px;">提交</button>
                    </div>
                </form>
            <?php else: ?>
            <?php endif ?></p>
            <div class="lab" style="margin-bottom:10px; font-weight:700;">考试信息</div>
            <div>
    			<table class="bordered">
    				<thead>
    					<tr>
    						<th>考试id</th>
    						<th>考试名</th>
    						<th>出卷老师</th>
    						<th>开考时间</th>
    						<th>结束时间</th>
    						<th>信息</th>
                            <?php if (session('utype') >= 2): ?>
                                <th>考题设置</th>
                            <?php else: ?>
                            <?php endif ?>
                            <th>成绩</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
    							<td><?php echo ($vo["id"]); ?></td>
    							<td><?php echo ($vo["exam_name"]); ?></td>
    							<td><?php echo ($vo["username"]); ?></td>
    							<td><?php echo ($vo["start_time"]); ?></td>
    							<td><?php echo ($vo["end_time"]); ?></td>
    							<td>
    								<a href="<?php echo U('showexam?eid='.$vo['id']);?>">进入考试</a>
    							</td>
                                <?php if (session('utype') >= 2): ?>
                                    <td><a href="<?php echo U('setproblem?id='.$vo['id']);?>">设置</a></td>
                                <?php else: ?>
                                <?php endif ?>
                                <td><a href="<?php echo U('Examstatus/examscore?eid='.$vo['id']);?>">查询</a></td>
    						</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    				</tbody>
    			</table>
            </div>
			<div class="pagelist"><?php echo ($page); ?></div>
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
</html>