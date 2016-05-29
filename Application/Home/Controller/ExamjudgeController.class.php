<?php
namespace Home\Controller;
use Think\Controller;

/**
* 
*/
class ExamjudgeController extends CommonController
{
	public function judgeproblem() {
		$pid = $_GET['pid'];
		if ($pid) {
			//创建judge表的Model,并将验证后的表单信息写入$submitdata中
			$examscore = D('examscore');
			if(!$submitdata = $examscore->create()) {
				//防止输出中文乱码
				header("Content-type: text/html; charset=utf-8");
				exit($examscore->getError());
			}

			//将提交时间和用户id写入$submitdata中
			$submitdata['submit_time'] = date("Y-m-d H:i:s");
			$submitdata['uid'] = session('id');
			$submitdata['answer'] = html_entity_decode($submitdata['answer']);

			//判断提交次数是否已超过限制
			$where_count = array();
			$where_count['epid'] = $submitdata['epid'];
			$where_count['uid'] = session('id');
			$submit_count = $examscore->where($where_count)->count();

			//获取考试时间
			$examproblem = D('examproblem');
			$where_submit = array();
			$where_submit['a.id'] =  $submitdata['epid'];
			$submit_times = $examproblem->alias('a')->field('a.submit_times, b.start_time, b.end_time')->where($where_submit)->join('think_exams b ON a.exam_id = b.id')->find();

			//考试时间控制
			$is_time_before = true;
			$is_time_after = true;
			$exam_start_time = strtotime($submit_times['start_time']);
			$exam_end_time = strtotime($submit_times['end_time']);
			$now_time = strtotime($submitdata['submit_time']);
			// var_dump($now_time);
			// var_dump($exam_start_time);
			// var_dump($exam_end_time);
			// var_dump($submitdata['submit_time']);
			// var_dump($submit_times['start_time']);
			// var_dump($submit_times['end_time']);
			if ($now_time >= $exam_start_time) {
				$is_time_before = false;
			}
			if ($now_time <= $exam_end_time) {
				$is_time_after = false;
			}

			if($submit_times['submit_times'] > $submit_count && !$is_time_before && !$is_time_after) {
				//创建problems表的Model,查询当前问题的答案
				$problem = D('problems');
				$where = array();
				$where['id'] = $pid;
				$tab_prbdata = $problem->where($where)->find();

				if($tab_prbdata['typeid'] != 1) {
					echo "<script>alert('题型不符')</script>";
					echo "<script>window.history.back(-1);</script>";
					return false;
				}

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
							elseif ($key_diff_result_smaller) {
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
				if ($examscore->add($submitdata)) {
					$this->redirect('Examstatus/personalstatus');
				}
				else {
					echo "<script>alert('提交失败，请重新提交');</script>";
            		echo "<script>window.history.back(-1);</script>";
				}
			}
			else if($is_time_before) {
				$this->error('考试未开始', U('Exam/listexam'),3);
			}
			else if ($is_time_after) {
				$this->error('考试已结束', U('Exam/listexam'),3);
			}
			else {
				echo "<script>alert('提交次数超过限制');</script>";
            	echo "<script>window.history.back(-1);</script>";
			}
		}
		else {
			echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function insertjudge() {
		$pid = $_GET['pid'];
		if ($pid) {
			//创建judge表的Model,并将验证后的表单信息写入$submitdata中
			$examscore = D('examscore');
			if(!$submitdata = $examscore->create()) {
				//防止输出中文乱码
				header("Content-type: text/html; charset=utf-8");
				exit($examscore->getError());
			}

			//将提交时间和用户id写入$submitdata中
			$submitdata['submit_time'] = date("Y-m-d H:i:s");
			$submitdata['uid'] = session('id');
			$submitdata['answer'] = html_entity_decode($submitdata['answer']);

			//判断提交次数是否已超过限制
			$where_count = array();
			$where_count['epid'] = $submitdata['epid'];
			$where_count['uid'] = session('id');
			$submit_count = $examscore->where($where_count)->count();

			$examproblem = D('examproblem');
			$where_submit = array();
			$where_submit['a.id'] =  $submitdata['epid'];
			$submit_times = $examproblem->alias('a')->field('a.submit_times, b.start_time, b.end_time')->where($where_submit)->join('think_exams b ON a.exam_id = b.id')->find();

			//考试时间控制
			$is_time_before = true;
			$is_time_after = true;
			$exam_start_time = strtotime($submit_times['start_time']);
			$exam_end_time = strtotime($submit_times['end_time']);
			$now_time = strtotime($submitdata['submit_time']);
			// var_dump($now_time);
			// var_dump($exam_start_time);
			// var_dump($exam_end_time);
			// var_dump($submitdata['submit_time']);
			// var_dump($submit_times['start_time']);
			// var_dump($submit_times['end_time']);
			if ($now_time >= $exam_start_time) {
				$is_time_before = false;
			}
			if ($now_time <= $exam_end_time) {
				$is_time_after = false;
			}

			if($submit_times['submit_times'] > $submit_count && !$is_time_before && !$is_time_after) {
				//创建problems表的Model,查询当前问题的答案
				$problem = D('problems');
				$where = array();
				$where['id'] = $pid;
				$tab_prbdata = $problem->where($where)->find();

				if($tab_prbdata['typeid'] != 2) {
					echo "<script>alert('题型不符')</script>";
					echo "<script>window.history.back(-1);</script>";
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
				if ($examscore->add($submitdata)) {
					$this->redirect('Examstatus/personalstatus');
				}
				else {
					echo "<script>alert('提交失败，请重新提交');</script>";
            		echo "<script>window.history.back(-1);</script>";
				}
			}
			else if($is_time_before) {
				$this->error('考试未开始', U('Exam/listexam'),3);
			}
			else if ($is_time_after) {
				$this->error('考试已结束', U('Exam/listexam'),3);
			}
			else {
				echo "<script>alert('提交次数超过限制');</script>";
            	echo "<script>window.history.back(-1);</script>";
			}
		}
		else {
			echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function deletejudge() {
		$pid = $_GET['pid'];
		if ($pid) {
			//创建judge表的Model,并将验证后的表单信息写入$submitdata中
			$examscore = D('examscore');
			if(!$submitdata = $examscore->create()) {
				//防止输出中文乱码
				header("Content-type: text/html; charset=utf-8");
				exit($examscore->getError());
			}

			//将提交时间和用户id写入$submitdata中
			$submitdata['submit_time'] = date("Y-m-d H:i:s");
			$submitdata['uid'] = session('id');
			$submitdata['answer'] = html_entity_decode($submitdata['answer']);

			//判断提交次数是否已超过限制
			$where_count = array();
			$where_count['epid'] = $submitdata['epid'];
			$where_count['uid'] = session('id');
			$submit_count = $examscore->where($where_count)->count();

			$examproblem = D('examproblem');
			$where_submit = array();
			$where_submit['a.id'] =  $submitdata['epid'];
			$submit_times = $examproblem->alias('a')->field('a.submit_times, b.start_time, b.end_time')->where($where_submit)->join('think_exams b ON a.exam_id = b.id')->find();

			//考试时间控制
			$is_time_before = true;
			$is_time_after = true;
			$exam_start_time = strtotime($submit_times['start_time']);
			$exam_end_time = strtotime($submit_times['end_time']);
			$now_time = strtotime($submitdata['submit_time']);
			// var_dump($now_time);
			// var_dump($exam_start_time);
			// var_dump($exam_end_time);
			// var_dump($submitdata['submit_time']);
			// var_dump($submit_times['start_time']);
			// var_dump($submit_times['end_time']);
			if ($now_time >= $exam_start_time) {
				$is_time_before = false;
			}
			if ($now_time <= $exam_end_time) {
				$is_time_after = false;
			}
			$is_time_before = false;
			$is_time_after = false;

			if($submit_times['submit_times'] > $submit_count && !$is_time_before && !$is_time_after) {
				//创建problems表的Model,查询当前问题的答案
				$problem = D('problems');
				$where = array();
				$where['id'] = $pid;
				$tab_prbdata = $problem->where($where)->find();

				if($tab_prbdata['typeid'] != 3) {
					echo "<script>alert('题型不符')</script>";
					echo "<script>window.history.back(-1);</script>";
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
						$submitdata['score'] = 0;
					}
					else {
						$submitdata['status'] = '删除范围过小';
						$submitdata['score'] = 0;
					}
				}
				$runmod->rollback();
				if ($examscore->add($submitdata)) {
					$this->redirect('Examstatus/personalstatus');
				}
				else {
					echo "<script>alert('提交失败，请重新提交');</script>";
            		echo "<script>window.history.back(-1);</script>";
				}
			}
			else if($is_time_before) {
				$this->error('考试未开始', U('Exam/listexam'),3);
			}
			else if ($is_time_after) {
				$this->error('考试已结束', U('Exam/listexam'),3);
			}
			else {
				echo "<script>alert('提交次数超过限制');</script>";
            	echo "<script>window.history.back(-1);</script>";
			}
		}
		else {
			echo "<script>alert('请求格式有误');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function updatejudge() {
		$pid = $_GET['pid'];
		if ($pid) {
			//创建judge表的Model,并将验证后的表单信息写入$submitdata中
			$examscore = D('examscore');
			if(!$submitdata = $examscore->create()) {
				//防止输出中文乱码
				header("Content-type: text/html; charset=utf-8");
				exit($examscore->getError());
			}

			//将提交时间和用户id写入$submitdata中
			$submitdata['submit_time'] = date("Y-m-d H:i:s");
			$submitdata['uid'] = session('id');
			$submitdata['answer'] = html_entity_decode($submitdata['answer']);

			//判断提交次数是否已超过限制
			$where_count = array();
			$where_count['epid'] = $submitdata['epid'];
			$where_count['uid'] = session('id');
			$submit_count = $examscore->where($where_count)->count();

			$examproblem = D('examproblem');
			$where_submit = array();
			$where_submit['a.id'] =  $submitdata['epid'];
			$submit_times = $examproblem->alias('a')->field('a.submit_times, b.start_time, b.end_time')->where($where_submit)->join('think_exams b ON a.exam_id = b.id')->find();

			//考试时间控制
			$is_time_before = true;
			$is_time_after = true;
			$exam_start_time = strtotime($submit_times['start_time']);
			$exam_end_time = strtotime($submit_times['end_time']);
			$now_time = strtotime($submitdata['submit_time']);
			// var_dump($now_time);
			// var_dump($exam_start_time);
			// var_dump($exam_end_time);
			// var_dump($submitdata['submit_time']);
			// var_dump($submit_times['start_time']);
			// var_dump($submit_times['end_time']);
			if ($now_time >= $exam_start_time) {
				$is_time_before = false;
			}
			if ($now_time <= $exam_end_time) {
				$is_time_after = false;
			}

			if($submit_times['submit_times'] > $submit_count && !$is_time_before && !$is_time_after) {
				//创建problems表的Model,查询当前问题的答案
				$problem = D('problems');
				$where = array();
				$where['id'] = $pid;
				$tab_prbdata = $problem->where($where)->find();

				// var_dump($tab_prbdata);
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

				//创建指向评分数据库的空Model
				$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');
				//开启事务模式
				$runmod->startTrans();

				$tea_col = $runmod->execute($update_msg['SQL']);
				$real_result = $runmod->query($select_sql);

				$runmod->rollback();
				// var_dump($tea_col);

				$runmod->startTrans();
				$stu_col = $runmod->execute($submitdata['answer']);
				$stu_result = $runmod->query($select_sql);
				$runmod->rollback();

				// var_dump($stu_col);

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
				// var_dump($submitdata);
				if ($examscore->add($submitdata)) {
					$this->redirect('Examstatus/personalstatus');
				}
				else {
					echo "<script>alert('提交失败，请重新提交');</script>";
            		echo "<script>window.history.back(-1);</script>";
				}
			}
			else if($is_time_before) {
				$this->error('考试未开始', U('Exam/listexam'),3);
			}
			else if ($is_time_after) {
				$this->error('考试已结束', U('Exam/listexam'),3);
			}
			else {
				echo "<script>alert('提交次数超过限制');</script>";
            	echo "<script>window.history.back(-1);</script>";
			}
		}
		else {
			echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function createjudge(){
		$pid = $_GET['pid'];
		if ($pid) {
			//创建judge表的Model,并将验证后的表单信息写入$submitdata中
			$examscore = D('examscore');
			if(!$submitdata = $examscore->create()) {
				//防止输出中文乱码
				header("Content-type: text/html; charset=utf-8");
				exit($examscore->getError());
			}

			//将提交时间和用户id写入$submitdata中
			$submitdata['submit_time'] = date("Y-m-d H:i:s");
			$submitdata['uid'] = session('id');
			$submitdata['answer'] = html_entity_decode($submitdata['answer']);

			//判断提交次数是否已超过限制
			$where_count = array();
			$where_count['epid'] = $submitdata['epid'];
			$where_count['uid'] = session('id');
			$submit_count = $examscore->where($where_count)->count();

			$examproblem = D('examproblem');
			$where_submit = array();
			$where_submit['a.id'] =  $submitdata['epid'];
			$submit_times = $examproblem->alias('a')->field('a.submit_times, b.start_time, b.end_time')->where($where_submit)->join('think_exams b ON a.exam_id = b.id')->find();

			//考试时间控制
			$is_time_before = true;
			$is_time_after = true;
			$exam_start_time = strtotime($submit_times['start_time']);
			$exam_end_time = strtotime($submit_times['end_time']);
			$now_time = strtotime($submitdata['submit_time']);
			// var_dump($now_time);
			// var_dump($exam_start_time);
			// var_dump($exam_end_time);
			// var_dump($submitdata['submit_time']);
			// var_dump($submit_times['start_time']);
			// var_dump($submit_times['end_time']);
			if ($now_time >= $exam_start_time) {
				$is_time_before = false;
			}
			if ($now_time <= $exam_end_time) {
				$is_time_after = false;
			}

			if($submit_times['submit_times'] > $submit_count && !$is_time_before && !$is_time_after) {
				//创建problems表的Model,查询当前问题的答案
				$problem = D('problems');
				$where = array();
				$where['id'] = $pid;
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

				if ($examscore->add($submitdata)) {
					$this->redirect('Examstatus/personalstatus');
				}
				else {
					echo "<script>alert('提交失败，请重新提交');</script>";
		            echo "<script>window.history.back(-1);</script>";
				}
			}
			else if($is_time_before) {
				$this->error('考试未开始', U('Exam/listexam'),3);
			}
			else if ($is_time_after) {
				$this->error('考试已结束', U('Exam/listexam'),3);
			}
			else {
				echo "<script>alert('提交次数超过限制');</script>";
            	echo "<script>window.history.back(-1);</script>";
			}
		}
		else {
			echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}

	public function insertjudge2() {
		$pid = $_GET['pid'];
		if ($pid) {
			//创建judge表的Model,并将验证后的表单信息写入$submitdata中
			$examscore = D('examscore');
			if(!$submitdata = $examscore->create()) {
				//防止输出中文乱码
				header("Content-type: text/html; charset=utf-8");
				exit($examscore->getError());
			}

			//将提交时间和用户id写入$submitdata中
			$submitdata['submit_time'] = date("Y-m-d H:i:s");
			$submitdata['uid'] = session('id');
			$submitdata['answer'] = html_entity_decode($submitdata['answer']);

			//判断提交次数是否已超过限制
			$where_count = array();
			$where_count['epid'] = $submitdata['epid'];
			$where_count['uid'] = session('id');
			$submit_count = $examscore->where($where_count)->count();

			$examproblem = D('examproblem');
			$where_submit = array();
			$where_submit['a.id'] =  $submitdata['epid'];
			$submit_times = $examproblem->alias('a')->field('a.submit_times, b.start_time, b.end_time')->where($where_submit)->join('think_exams b ON a.exam_id = b.id')->find();

			//考试时间控制
			$is_time_before = true;
			$is_time_after = true;
			$exam_start_time = strtotime($submit_times['start_time']);
			$exam_end_time = strtotime($submit_times['end_time']);
			$now_time = strtotime($submitdata['submit_time']);
			// var_dump($now_time);
			// var_dump($exam_start_time);
			// var_dump($exam_end_time);
			// var_dump($submitdata['submit_time']);
			// var_dump($submit_times['start_time']);
			// var_dump($submit_times['end_time']);
			if ($now_time >= $exam_start_time) {
				$is_time_before = false;
			}
			if ($now_time <= $exam_end_time) {
				$is_time_after = false;
			}

			if($submit_times['submit_times'] > $submit_count && !$is_time_before && !$is_time_after) {
				//创建problems表的Model,查询当前问题的答案
				$problem = D('problems');
				$where = array();
				$where['id'] = $pid;
				$tab_prbdata = $problem->where($where)->find();

				if($tab_prbdata['typeid'] != 2) {
					echo "<script>alert('题型不符')</script>";
					echo "<script>window.history.back(-1);</script>";
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
				// var_dump($insert_msg);

				//创建指向评分数据库的空Model
				$runmod = M('', 'think_', 'mysql://stuuser:135246@localhost/judge_data');
				//开启事务模式
				$runmod->startTrans();

				$tea_col = $runmod->execute($insert_msg['SQL']);
				$real_result = $runmod->query($select_sql);

				$runmod->rollback();
				// var_dump($tea_col);

				$runmod->startTrans();
				$stu_col = $runmod->execute($submitdata['answer']);
				$stu_result = $runmod->query($select_sql);
				$runmod->rollback();

				// var_dump($stu_col);

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
				if ($examscore->add($submitdata)) {
					$this->redirect('Examstatus/personalstatus');
				}
				else {
					echo "<script>alert('提交失败，请重新提交');</script>";
            		echo "<script>window.history.back(-1);</script>";
				}
			}
			else if($is_time_before) {
				$this->error('考试未开始', U('Exam/listexam'),3);
			}
			else if ($is_time_after) {
				$this->error('考试已结束', U('Exam/listexam'),3);
			}
			else {
				echo "<script>alert('提交次数超过限制');</script>";
            	echo "<script>window.history.back(-1);</script>";
			}
		}
		else {
			echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
		}
	}
}