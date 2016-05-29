<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>SetProblem</title>
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
			<div style="display:none;">
				<p><?php echo ($examid); ?></p>
				<p><?php echo U('addexamproblem');?></p>
			</div>
            <div class="lab-full" style="margin-bottom:10px; font-weight:700;">
                已添加题目
                <a href="<?php echo ($prourl); ?>" style="float:right;margin-right:30px;">添加题目</a>
            </div>
			<div id="problemlist">
				<?php if($tablecheck): ?><table class="bordered">
						<thead>
							<tr>
								<th>考试id</th>
								<th>题目id</th>
								<th>题目序号</th>
								<th>最大提交次数</th>
                                <th>删除</th>
							</tr>
						</thead>
						<tbody id="dataBody">
							<?php if(is_array($tabledata)): $i = 0; $__LIST__ = $tabledata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
									<td><?php echo ($vo["exam_id"]); ?></td>
									<td class="problem_id"><?php echo ($vo["pid"]); ?></td>
									<td><?php echo ($vo["problem_order"]); ?></td>
									<td><?php echo ($vo["submit_times"]); ?></td>
                                    <td><p id="<?php echo ($vo["pid"]); ?>">删除</p></td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						</tbody>
					</table>
					<?php else: ?>
					<div class="description" id="problemnone">该考试还未设定题库</div><?php endif; ?>
			</div>
            <div class="lab" style="margin-top:10px; margin-bottom:10px;">添加题目信息</div>
			<div>
				<form name="addproblem" id="addprb">
                    <div class="inputdiv" id="prbinput">
    					<input type="text" name="exam_id" value=<?php echo ($examid); ?> style="display:none">
    					题目id: <input type="text" name="pid" id="proid" placeholder="ProblemId">
    					题目序号: <input type="text" name="problem_order" placeholder="ProblemOrder">
    					最大提交次数: <input type="text" name="submit_times" placeholder="MaxSubmitTimes">
                    
					    <button id="subbtn" style="margin-right:20px; margin-top:11px;">添加考题</button>
                    </div>
				</form>
			</div>
            <?php if($tablecheck): else: ?>
                <div class="lab" style="margin-top:10px; margin-bottom:10px;">随机添加题目</div>
                <div>
                    <form name="addrandexamproblem" action="<?php echo U('addrandexamproblem');?>" method="post">
                        <div class="inputdiv">
                            <input type="text" name="eid" value="<?php echo ($examid); ?>" style="display:none">
                            查找：<input type="text" name="select_num" placeholder="查找题数量">
                            插入：<input type="text" name="insert_num" placeholder="插入题数量">
                            删除：<input type="text" name="delete_num" placeholder="删除题数量">
                            更新：<input type="text" name="update_num" placeholder="更新题数量">
                            <button type="submit" style="margin-right:3px; margin-top:11px;">提交</button>
                        </div>
                    </form>
                </div><?php endif; ?>
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
<script>
    var eid = <?php echo ($examid); ?>;
</script>
<script src="/Public/JS/jquery.js"></script>
<script src="/Public/JS/addexamproblem.js"></script>
</html>