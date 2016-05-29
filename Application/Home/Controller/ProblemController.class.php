<?php
namespace Home\Controller;
use Think\Controller;

/**
* 
*/
class ProblemController extends CommonController
{
	public function _before_add() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }
	
	public function add() {
		if(IS_POST) {
			$problem = D('problems');
			if (!$data = $problem->create()) {
                // 防止输出中文乱码
                header("Content-type: text/html; charset=utf-8");
                exit($problem->getError());
            }
            $data['answer'] = html_entity_decode($data['answer']);
            if($problem->add($data)) {
            	$this->success('添加题目成功', $this, 2);
            }
            else {
            	$this->error('添加题目失败！');
            }
		}
		else {
			$this->display();
		}
	}

	public function _before_update() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

	public function update() {
		if(IS_POST) {
			$problem = D('problems');
			if (!$data = $problem->create()) {
                // 防止输出中文乱码
                header("Content-type: text/html; charset=utf-8");
                exit($problem->getError());
            }
            $data['answer'] = html_entity_decode($data['answer']);
            $where = array();
            $where['id'] = $data['id'];
            if($problem->where($where)->save($data)) {
            	$this->success('更新题目成功', $this, 2);
            }
            else {
            	$this->error('更新题目失败！');
            }
		}
		else {
			$pid = $_GET['pid'];
			$problem = D('problems');
			$where = array();
			$where['id'] = $pid;
			$result = $problem->where($where)->find();
			if ($result) {
				$this->assign('data', $result);
				$this->display();
			}
			else {
				$this->error('无效题目');
			}
		}
	}

	public function listproblem() {
		$problem = M('problems');
		$count = $problem->count();
		$Page = new \Think\Page($count,25);

		$Page->lastSuffix = false;

		$Page->setConfig('header', $count.'篇记录');
		$Page->setConfig('prev', '上一页');
		$Page->setConfig('next', '下一页');
		$Page->setConfig('first', '首页');
		$Page->setConfig('last', '末页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

		$show = $Page->show();
		$list = $problem->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	public function showproblem() {
		$pid = $_GET['pid'];
		if ($pid) {
			$problem = D('problems');
			$where = array();
			$where['id'] = $pid;
			$result = $problem->where($where)->find();

			if ($result) {
				switch ($result['typeid']) {
					case '1':
						$submiturl = U('judgeproblem');
						$result['type'] = '查找题';
						break;
					case '2':
						$submiturl = U('insertjudge2');
						$result['type'] = '插入题';
						break;
					case '3':
						$submiturl = U('deletejudge');
						$result['type'] = '删除题';
						break;
					case '4':
						$submiturl = U('updatejudge');
						$result['type'] = '更新题';
						break;
					case '5':
						$submiturl = U('createjudge');
						$result['type'] = '建表题';
						break;
					default:
						$submiturl = '';
						$result['type'] = '';
						break;
				}
				$this->assign('submiturl', $submiturl);
				$this->assign('data', $result);
				$this->display();
			}
		}
		else {
			$this->error('无效题目');
		}
	}

	public function judgeproblem() {
		//创建judge表的Model,并将验证后的表单信息写入$submitdata中
		$judge = D('judge');
		if(!$submitdata = $judge->create()) {
			//防止输出中文乱码
			header("Content-type: text/html; charset=utf-8");
			exit($judge->getError());
		}

		//将提交时间和用户id写入$submitdata中
		$submitdata['submit_time'] = date("Y-m-d H:i:s");
		$submitdata['uid'] = session('id');
		$submitdata['answer'] = html_entity_decode($submitdata['answer']);

		//创建problems表的Model,查询当前问题的答案
		$problem = D('problems');
		$where = array();
		$where['id'] = $submitdata['pid'];
		$tab_prbdata = $problem->where($where)->find();

		if($tab_prbdata['typeid'] != 1) {
			echo "<script>alert('题型不符')</script>";
			return false;
		}
		var_dump($submitdata);

		//创建指向评分数据库的空Model
		$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');

		//运行学生提交的答案
		$stu_result = $runmod->query($submitdata['answer']);

		if ($stu_result === false) {
			$error_of_db = $runmod->getDbError();
			$submitdata['status'] = $error_of_db;
			$submitdata['score'] = 0;
		}
		else if (empty($stu_result)) {
			$submitdata['status'] = '查询结果为空';
			$submitdata['score'] = 0;
		}
		else {
			//运行正确答案
			$real_result = $runmod->query($tab_prbdata['answer']);

			$key_flag = false;
			$val_flag = false;

			if(count($stu_result) != count($real_result)) {
				$submitdata['status'] = '查询结果的数据数量不符合要求';
				$submitdata['score'] = 2;
			}
			else {
				$result_count = count($stu_result);
				for ($i=0; $i < $result_count; $i++) {
					$key_diff_result = array_diff_key($stu_result[$i], $real_result[$i]);
					$key_diff_result_smaller = array_diff_key($real_result[$i], $stu_result[$i]); 
					$diff_result = array_diff_assoc($stu_result[$i], $real_result[$i]);
					$diff_result_smaller = array_diff_assoc($real_result[$i], $stu_result[$i]);
					if ($key_diff_result) {
						$key_flag = true;
						break;
					}
					else if ($key_diff_result_smaller) {
						$key_flag = true;
						break;
					}
					else if($diff_result) {
						$val_flag = true;
						break;
					}
					else if($diff_result_smaller) {
						$val_flag = true;
						break;
					}
				}
				if ($key_flag) {
					$submitdata['status'] = '查询结果的属性过多或过少';
					$submitdata['score'] = 3;
				}
				else if ($val_flag) {
					$submitdata['status'] = '查询结果的数据排序出错或部分数据出现错误';
					$submitdata['score'] = 6;
				}
				else{
					$submitdata['status'] = '答案正确';
					$submitdata['score'] = 10;
				}
			}
		}
		if ($judge->add($submitdata)) {
			$this->redirect('Status/status');
		}
		else {
			echo "<script>alert('提交失败，请重新提交');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function insertjudge(){
		//创建judge表的Model,并将验证后的表单信息写入$submitdata中
		$judge = D('judge');
		if(!$submitdata = $judge->create()) {
			//防止输出中文乱码
			header("Content-type: text/html; charset=utf-8");
			exit($judge->getError());
		}

		//将提交时间和用户id写入$submitdata中
		$submitdata['submit_time'] = date("Y-m-d H:i:s");
		$submitdata['uid'] = session('id');
		$submitdata['answer'] = html_entity_decode($submitdata['answer']);

		//创建problems表的Model,查询当前问题的答案
		$problem = D('problems');
		$where = array();
		$where['id'] = $submitdata['pid'];
		$tab_prbdata = $problem->where($where)->find();

		if($tab_prbdata['typeid'] != 2) {
			echo "<script>alert('题型不符')</script>";
			return false;
		}

		//从标准答案中分割出插入的信息
		$insert_msg = explode('/', $tab_prbdata['answer']);
		$count_sql = 'select count(1) from ' . $insert_msg[1] . ' where ';
		$i = 2;
		while($i < count($insert_msg)) {
			$count_sql = $count_sql . $insert_msg[$i] . '=' . $insert_msg[$i+1] . ' ';
			if (($i+2) != count($insert_msg)) {
				$count_sql = $count_sql . 'and ';
			}
			$i += 2;
		}
		$count_sql = $count_sql . ';';

		//创建指向评分数据库的空Model
		$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');
		//开启事务模式
		$runmod->startTrans();

		$data_count_before = $runmod->query($count_sql);
		$error_msg = $runmod->execute($submitdata['answer']);
		if ($error_msg === false) {
			$submitdata['status'] = $runmod->getDbError();
			$submitdata['score'] = 0;
		}
		else if ($error_msg === 0) {
			$submitdata['status'] = '插入行数为0';
			$submitdata['score'] = 0;
		}
		else {
			$data_count_after = $runmod->query($count_sql);
			if ($data_count_after[0]['count(1)'] == ($data_count_before[0]['count(1)']+1)) {
				$submitdata['status'] = '答案正确';
				$submitdata['score'] = 10;
			}
			else {
				$submitdata['status'] = '插入数据不正确';
				$submitdata['score'] = 0;
			}
		}
		$runmod->rollback();
		if ($judge->add($submitdata)) {
			print_r($submitdata['answer']);
			$this->redirect('Status/status');
		}
		else {
			echo "<script>alert('提交失败，请重新提交');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function deletejudge(){
		//创建judge表的Model,并将验证后的表单信息写入$submitdata中
		$judge = D('judge');
		if(!$submitdata = $judge->create()) {
			//防止输出中文乱码
			header("Content-type: text/html; charset=utf-8");
			exit($judge->getError());
		}

		//将提交时间和用户id写入$submitdata中
		$submitdata['submit_time'] = date("Y-m-d H:i:s");
		$submitdata['uid'] = session('id');
		$submitdata['answer'] = html_entity_decode($submitdata['answer']);

		//创建problems表的Model,查询当前问题的答案
		$problem = D('problems');
		$where = array();
		$where['id'] = $submitdata['pid'];
		$tab_prbdata = $problem->where($where)->find();

		if($tab_prbdata['typeid'] != 3) {
			echo "<script>alert('题型不符')</script>";
			return false;
		}

		//创建指向评分数据库的空Model
		$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');
		//开启事务模式
		$runmod->startTrans();

		$runmod->execute($tab_prbdata['answer']);
		$col_num = $runmod->execute($submitdata['answer']);

		if ($col_num === 0) {
			$biggerflag = 1;
		}
		else if($col_num > 0) {
			$biggerflag = 0;
			$submitdata['status'] = '删除范围过大';
			$submitdata['score'] = 0;
		}
		else if($col_num === false) {
			$submitdata['status'] = $runmod->getDbError();
			$submitdata['score'] = 0;
			$biggerflag = 0;
		}

		$runmod->rollback();

		$runmod->startTrans();

		if ($biggerflag == 1) {
			$runmod->execute($submitdata['answer']);
			$col_num = $runmod->execute($tab_prbdata['answer']);
			if ($col_num === 0) {
				$submitdata['status'] = '答案正确';
				$submitdata['score'] = 10;
			}
			else {
				$submitdata['status'] = '删除范围过小';
				$submitdata['score'] = 0;
			}
		}
		$runmod->rollback();
		if ($judge->add($submitdata)) {
			$this->redirect('Status/status');
		}
		else {
			echo "<script>alert('提交失败，请重新提交');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function updatejudge() {
		//创建judge表的Model,并将验证后的表单信息写入$submitdata中
		$judge = D('judge');
		if(!$submitdata = $judge->create()) {
			//防止输出中文乱码
			header("Content-type: text/html; charset=utf-8");
			exit($judge->getError());
		}

		//将提交时间和用户id写入$submitdata中
		$submitdata['submit_time'] = date("Y-m-d H:i:s");
		$submitdata['uid'] = session('id');
		$submitdata['answer'] = html_entity_decode($submitdata['answer']);

		//创建problems表的Model,查询当前问题的答案
		$problem = D('problems');
		$where = array();
		$where['id'] = $submitdata['pid'];
		$tab_prbdata = $problem->where($where)->find();

		if($tab_prbdata['typeid'] != 4) {
			echo "<script>alert('题型不符')</script>";
			return false;
		}

		//从标准答案中分割出插入的信息
		$temp = explode('/', $tab_prbdata['answer']);
		$update_msg = array();
		$i = 0;
		while($i < count($temp)) {
			$update_msg[$temp[$i]] = $temp[$i+1];
			$i += 2;
		}
		$select_sql = 'select * from ' . $update_msg['table'] . ';';
		var_dump($update_msg);

		//创建指向评分数据库的空Model
		$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');
		//开启事务模式
		$runmod->startTrans();

		$tea_col = $runmod->execute($update_msg['SQL']);
		$real_result = $runmod->query($select_sql);

		$runmod->rollback();
		var_dump($tea_col);

		$runmod->startTrans();
		$stu_col = $runmod->execute($submitdata['answer']);
		$stu_result = $runmod->query($select_sql);
		$runmod->rollback();

		var_dump($stu_col);

		if ($stu_col === $tea_col) {
			$biggerflag = 1;
		}
		else if($stu_col === false) {
			$submitdata['status'] = $runmod->getDbError();
			$submitdata['score'] = 0;
			$biggerflag = 0;
		}
		else if($stu_col > $tea_col) {
			$biggerflag = 0;
			$submitdata['status'] = '更新范围过大';
			$submitdata['score'] = 2;
		}
		else if($stu_col < $tea_col) {
			$biggerflag = 0;
			$submitdata['status'] = '更新范围过小';
			$submitdata['score'] = 2;
		}

		if($biggerflag) {
			$diff_flag = false;
			$result_count = count($stu_result);
			for ($i=0; $i < $result_count; $i++) {
				$diff_result = array_diff_assoc($real_result[$i], $stu_result[$i]);
				if ($diff_result) {
					$diff_flag = true;
					break;
				}
			}

			if ($diff_flag) {
				$submitdata['status'] = '更新后部分数据不对应';
				$submitdata['score'] = 5;
			}
			else {
				$submitdata['status'] = '答案正确';
				$submitdata['score'] = 10;
			}
		}

		var_dump($submitdata);
		if ($judge->add($submitdata)) {
			$this->redirect('Status/status');
		}
		else {
			echo "<script>alert('提交失败，请重新提交');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function insertjudge2() {
		//创建judge表的Model,并将验证后的表单信息写入$submitdata中
		$judge = D('judge');
		if(!$submitdata = $judge->create()) {
			//防止输出中文乱码
			header("Content-type: text/html; charset=utf-8");
			exit($judge->getError());
		}

		//将提交时间和用户id写入$submitdata中
		$submitdata['submit_time'] = date("Y-m-d H:i:s");
		$submitdata['uid'] = session('id');
		$submitdata['answer'] = html_entity_decode($submitdata['answer']);

		//创建problems表的Model,查询当前问题的答案
		$problem = D('problems');
		$where = array();
		$where['id'] = $submitdata['pid'];
		$tab_prbdata = $problem->where($where)->find();

		if($tab_prbdata['typeid'] != 2) {
			echo "<script>alert('题型不符')</script>";
			return false;
		}

		//从标准答案中分割出插入的信息
		$temp = explode('/', $tab_prbdata['answer']);
		$insert_msg = array();
		$i = 0;
		while($i < count($temp)) {
			$insert_msg[$temp[$i]] = $temp[$i+1];
			$i += 2;
		}
		$select_sql = 'select * from ' . $insert_msg['table'] . ';';
		var_dump($insert_msg);

		//创建指向评分数据库的空Model
		$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');
		//开启事务模式
		$runmod->startTrans();

		$tea_col = $runmod->execute($insert_msg['SQL']);
		$real_result = $runmod->query($select_sql);

		$runmod->rollback();
		var_dump($tea_col);

		$runmod->startTrans();
		$stu_col = $runmod->execute($submitdata['answer']);
		$stu_result = $runmod->query($select_sql);
		$runmod->rollback();

		var_dump($stu_col);

		if ($stu_col === $tea_col) {
			$biggerflag = 1;
		}
		else if($stu_col === false) {
			$submitdata['status'] = $runmod->getDbError();
			$submitdata['score'] = 0;
			$biggerflag = 0;
		}
		else if($stu_col > $tea_col) {
			$biggerflag = 0;
			$submitdata['status'] = '插入记录数量过多';
			$submitdata['score'] = 2;
		}
		else if($stu_col < $tea_col) {
			$biggerflag = 0;
			$submitdata['status'] = '插入记录数量过少';
			$submitdata['score'] = 2;
		}

		if($biggerflag) {
			$diff_flag = false;
			$result_count = count($stu_result);
			for ($i=0; $i < $result_count; $i++) {
				$diff_result = array_diff_assoc($real_result[$i], $stu_result[$i]);
				if ($diff_result) {
					$diff_flag = true;
					break;
				}
			}

			if ($diff_flag) {
				$submitdata['status'] = '插入部分数据不对应';
				$submitdata['score'] = 5;
			}
			else {
				$submitdata['status'] = '答案正确';
				$submitdata['score'] = 10;
			}
		}

		var_dump($submitdata);
		if ($judge->add($submitdata)) {
			$this->redirect('Status/status');
		}
		else {
			echo "<script>alert('提交失败，请重新提交');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function createjudge(){
		//创建judge表的Model,并将验证后的表单信息写入$submitdata中
		$judge = D('judge');
		if(!$submitdata = $judge->create()) {
			//防止输出中文乱码
			header("Content-type: text/html; charset=utf-8");
			exit($judge->getError());
		}

		//将提交时间和用户id写入$submitdata中
		$submitdata['submit_time'] = date("Y-m-d H:i:s");
		$submitdata['uid'] = session('id');
		$submitdata['answer'] = html_entity_decode($submitdata['answer']);

		//创建problems表的Model,查询当前问题的答案
		$problem = D('problems');
		$where = array();
		$where['id'] = $submitdata['pid'];
		$tab_prbdata = $problem->where($where)->find();

		if($tab_prbdata['typeid'] != 5) {
			echo "<script>alert('题型不符')</script>";
			return false;
		}

		//从标准答案中分割出插入的信息
		$temp = array();
		$temp = explode('/', $tab_prbdata['answer']);

		$i = 0;
		while($i < count($temp)) {
			$table_msg[$temp[$i]] = $temp[$i+1];
			$i += 2;
		}
		
		$drop_sql = 'drop table IF EXISTS ' . $table_msg['table'] . ';';
		$show_sql = 'show columns from ' . $table_msg['table'] . ';';
		$field_array = explode(',', $table_msg['field']);

		//创建指向评分数据库的空Model
		$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/create_data');
		// $runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');
		
		$runmod->execute($drop_sql);
		$result = $runmod->execute($submitdata['answer']);
		if ($result === false) {
			$submitdata['status'] = $runmod->getDbError();
			$submitdata['score'] = 0;
		}
		else {
			$table_show = $runmod->query($show_sql);
			if ($table_show == 0) {
				$submitdata['status'] = '建表名字不符合题意';
				$submitdata['score'] = 0.2 * $tab_prbdata['score'];
			}
			else if (count($field_array) != count($table_show)) {
				$submitdata['status'] = '数据表属性数量不符合题意';
				$submitdata['score'] = 0.2 * $tab_prbdata['score'];
			}
			else {
				$stu_field = array();
				for ($i=0; $i < count($table_show); $i++) { 
					$stu_field[$i] = $table_show[$i]['field'];
				}
				$diff_result = array_diff($stu_field, $field_array);
				if ($diff_result) {
					$submitdata['status'] = '数据表部分属性不符合题意';
					$submitdata['score'] = 0.3 * $tab_prbdata['score'];
				}
				else {
					$submitdata['status'] = '答案正确';
					$submitdata['score'] = $tab_prbdata['score'];
				}
			}
		}
		// $runmod->execute($drop_sql);


		$tempmod = M('', 'think_', 'mysql://root:135246@localhost/information_schema');
		$drop_array = $tempmod->query('select concat("drop table ",table_name,";") from TABLES where table_schema="create_data";');
		for ($i=0; $i < count($drop_array); $i++) { 
			$runmod->execute($drop_array[$i]['concat("drop table ",table_name,";")']);
		}

		// var_dump($drop_array);

		// var_dump($result);
		// var_dump($table_show);
		// var_dump($diff_result);
		// var_dump($submitdata);

		if ($judge->add($submitdata)) {
			print_r($submitdata['answer']);
			$this->redirect('Status/status');
		}
		else {
			echo "<script>alert('提交失败，请重新提交');</script>";
            echo "<script>window.history.back(-1);</script>";
		}

	}
}