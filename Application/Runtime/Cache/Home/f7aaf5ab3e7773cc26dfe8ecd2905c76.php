<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>Exam problem</title>
	<link rel="stylesheet" type="text/css" href="/Public/CSS/pagelist.css">
	<link rel="stylesheet" type="text/css" href="/Public/CSS/main.css">
	<link rel="stylesheet" type="text/css" href="/Public/CSS/table.css">
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
            <div class="lab" style="margin-bottom:10px; font-weight:700;">考试信息</div>
			<div>
				<table class="bordered">
					<thead>
						<tr>
							<th>考试id</th>
							<th>序号</th>
							<th>题型</th>
							<th>题目名</th>
							<th>最大可提交次数</th>
							<th>分数</th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td><?php echo ($vo["exam_id"]); ?></td>
								<td><?php echo ($vo["problem_order"]); ?></td>
								<td>
									<?php switch($vo['typeid']): case "1": ?>查找题<?php break;?>
										<?php case "2": ?>插入题<?php break;?>
										<?php case "3": ?>删除题<?php break;?>
										<?php case "4": ?>更新题<?php break;?>
                                        <?php case "5": ?>建表题<?php break; endswitch;?>
								</td>
								<td>
									<a href="<?php echo U('showexamproblem?epid='.$vo['id']);?>"><?php echo ($vo["title"]); ?></a>
								</td>
								<td><?php echo ($vo["submit_times"]); ?></td>
								<td><?php echo ($vo["score"]); ?></td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
			</div>
			<div class="time">
			    <span id="t_d">00天</span>
			    <span id="t_h">00时</span>
			    <span id="t_m">00分</span>
			    <span id="t_s">00秒</span>
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