<?php
namespace Home\Controller;
use Think\Controller;
/**
* 
*/
class ExamController extends CommonController
{

    public function _before_add() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

	public function add() {
		if (IS_POST) {
			$exam = D('exams');
			if (!$data = $exam->create()) {
                // 防止输出中文乱码
                header("Content-type: text/html; charset=utf-8");
                exit($exam->getError());
            }
            //插入数据库
            if ($lastInsid = $exam->add($data)) {
                $url = 'setproblem?id=' . (string)$lastInsid;
                $this->success('添加考试成功', U($url), 2);
            } else {
                $this->error('添加考试失败');
            }
		}
        else {
            $teacher = session('id');
            $this->assign('teacher', $teacher);
            $this->display();
        }
	}

    public function _before_setproblem() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

    public function setproblem() {
        $id = $_GET['id'];
        if($id) {
            $exam = D('exams');
            $where = array();
            $where['id'] = $id;
            $result = $exam->where($where)->find();

            if($result) {
                $this->assign('examid', $id);

                $examproblem = D('examproblem');
                $condition = array();
                $condition['exam_id'] = $id;
                $problem_result = $examproblem->where($condition)->select();
                if($problem_result) {
                    $bool_result = true;
                }
                else {
                    $bool_result = false;
                }
                $this->assign('tablecheck', $bool_result);
                $this->assign('tabledata', $problem_result);
                $this->assign('examid', $id);
                $problem_url = U('listproblem?eid='.$id);
                $this->assign('prourl', $problem_url);

                $this->display();
            }
            else {
                $this->error('该考试不存在！');
            }
        }
        else {
            $this->error('考试id为空！');
        }
    }

    public function _before_listproblem() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

    public function listproblem() {
        if ($_GET['eid']) {
            $examid = I('get.eid');
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
            $this->assign('examid', $examid);

            $problem_url = U('setproblem?id='.$examid);
            $this->assign('prourl', $problem_url);

            $this->display();
        }
        else {
            echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
        }
    }

    public function _before_addexamproblem() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

    public function addexamproblem() {
        if (IS_POST) {
            $examproblem = D('examproblem');
            if (!$data = $examproblem->create()) {
                // 防止输出中文乱码
                header("Content-type: text/html; charset=utf-8");
                exit($examproblem->getError());
            }
            //插入数据库
            // var_dump($data);
            $rt_data = array();
            if ($examproblem->add($data)) {
                $rt_data['status'] = 1;
                $this->ajaxReturn($rt_data);
            } else {
                $rt_data['status'] = 0;
                $this->ajaxReturn($rt_data);
            }
        }
    }

    public function _before_addrandexamproblem() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

    public function addrandexamproblem() {
        if (IS_POST) {
            $select_num = $_POST['select_num'];
            $insert_num = $_POST['insert_num'];
            $delete_num = $_POST['delete_num'];
            $update_num = $_POST['update_num'];
            $eid = $_POST['eid'];

            $problem = D('problems');
            $result = $problem->order('typeid, id')->select();
            $count = count($result)-1;

            $select_begin = 0;
            $insert_begin = FindTypeId($select_begin, $count, $result, 2);
            $delete_begin = FindTypeId($insert_begin, $count, $result, 3);
            $update_begin = FindTypeId($delete_begin, $count, $result, 4);

            $select_array = NoRank($select_begin, ($insert_begin-1), $select_num);
            $insert_array = NoRank($insert_begin, ($delete_begin-1), $insert_num);
            $delete_array = NoRank($delete_begin, ($update_begin-1), $delete_num);
            $update_array = NoRank($update_begin, $count, $update_num);

            $problem_array = array_merge($select_array, $insert_array, $delete_array, $update_array);
            shuffle($problem_array);
            $data = array();
            for ($i=0; $i < count($problem_array); $i++) { 
                $data[$i]['exam_id'] = $eid;
                $data[$i]['problem_order'] = $i+1;
                $data[$i]['pid'] = $result[$problem_array[$i]]['id'];
                $data[$i]['submit_times'] = 5;
            }

            var_dump($data);
            $examproblem = D('examproblem');
            if ($examproblem->addall($data)) {
                echo "<script>alert('随机添加试题成功');</script>";
                $this->redirect('setproblem?id='.$eid);
            } else {
                echo "<script>alert('无效考试');</script>";
                echo "<script>window.history.back(-1);</script>";
            }

        }
    }

    public function _before_deleteexamproblem() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

    public function deleteexamproblem() {
        if (IS_GET) {
            $eid = $_GET['eid'];
            $order = $_GET['order'];
            if ($eid && $order) {
                $examproblem = D('examproblem');
                $where = array();
                $where['exam_id'] = $eid;
                $where['pid'] = $order;
                $rt_data = array();
                if ($examproblem->where($where)->delete()) {
                    $rt_data['status'] = 1;
                    $this->ajaxReturn($rt_data);
                }
                else {
                    $rt_data['status'] = 0;
                    $this->ajaxReturn($rt_data);
                }
            }
        }
    }

    public function _before_addexamproblemwithoutorder() {
        if (session('utype') < 2) {
            $this->error('你无权利使用此功能', U('Index/index'), 3);
        }
    }

    public function addexamproblemwithoutorder() {
        if (IS_POST) {
            $examproblem = M('examproblem');
            // if (!$data = $examproblem->create()) {
            //     // 防止输出中文乱码
            //     header("Content-type: text/html; charset=utf-8");
            //     exit($examproblem->getError());
            // }
            //插入数据库
            $strcheck = '';
            $data = $_POST['selectnums'];
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]['problem_order'] = 1;
                $data[$i]['submit_times'] = 999;
                if($i === 0) {
                    $strcheck = $strcheck . $data[$i]['pid'];
                }
                else {
                    $strcheck = $strcheck . ',' . $data[$i]['pid'];
                }
            }
            $where = array();
            $where['pid'] = array('in', $strcheck);
            $where['exam_id'] = $data[0]['exam_id'];
            $result = $examproblem->where($where)->select();
            $rt_data = array();
            if($result) {
                $rt_data['status'] = 2;
                $this->ajaxReturn($rt_data);
            }
            else {
            // var_dump($data);
            
                if ($examproblem->addAll($data)) {
                    $rt_data['status'] = 1;
                    $this->ajaxReturn($rt_data);
                } else {
                    $rt_data['status'] = 0;
                    $this->ajaxReturn($rt_data);
                }
            }
        }
    }

    public function listexam() {
        $exam = D('exams');
        $count = $exam->count();
        $Page = new \Think\Page($count,10);

        $Page->lastSuffix = false;

        $Page->setConfig('header', $count.'篇记录');
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '末页');
        $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

        $show = $Page->show();
        $list = $exam->alias('a')->field('a.id, a.exam_name, a.start_time, a.end_time, b.username')->join('think_users b ON a.teacher_id = b.id', 'LEFT')->order('start_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $teacher = session('id');
        $this->assign('teacher', $teacher);

        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    public function showexam() {
        $eid = $_GET['eid'];
        if ($eid) {
            $examproblem = D('examproblem');
            $where = array();
            $where['exam_id'] = $eid;
            $result = $examproblem->alias('a')->field('a.id, a.exam_id, a.pid, a.problem_order, a.submit_times, b.typeid, b.title, b.score')->where($where)->join('think_problems b ON b.id = a.pid')->select();
            
            $exam = D('exams');
            $exam_where['id'] = $eid;
            $exam_result = $exam->where($exam_where)->find();
            if ($result && $exam_result) {               
                $this->assign('data', $result);
                $this->assign('exam', $exam_result);
                $this->display();
            }
            else {
                echo "<script>alert('无效考试');</script>";
                echo "<script>window.history.back(-1);</script>";
            }
        }
        else {
            echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
        }
    }

    public function showexamproblem() {
        $epid = $_GET['epid'];
        if ($epid) {
            $examproblem = D('examproblem');
            $where = array();
            $where['a.id'] = $epid;
            $result = $examproblem->alias('a')->field('a.id, a.exam_id, a.pid, a.problem_order, a.submit_times, b.typeid, b.title, b.content')->where($where)->join('think_problems b ON b.id = a.pid')->find();

            $exam = D('exams');
            $exam_where['id'] = $result['exam_id'];
            $exam_result = $exam->where($exam_where)->find();
            // print_r($result);

            if ($result && $exam_result) {
                switch ($result['typeid']) {
                    case '1':
                        $submiturl = U('Examjudge/judgeproblem?pid='.$result['pid']);
                        $result['type'] = '查找题';
                        break;
                    case '2':
                        $submiturl = U('Examjudge/insertjudge2?pid='.$result['pid']);
                        $result['type'] = '插入题';
                        break;
                    case '3':
                        $submiturl = U('Examjudge/deletejudge?pid='.$result['pid']);
                        $result['type'] = '删除题';
                        break;
                    case '4':
                        $submiturl = U('Examjudge/updatejudge?pid='.$result['pid']);
                        $result['type'] = '更新题';
                        break;
                    case '5':
                        $submiturl = U('Examjudge/createjudge?pid='.$result['pid']);
                        $result['type'] = '建表题';
                        break;
                    default:
                        $submiturl = '';
                        $result['type'] = '';
                        break;
                }
                $this->assign('submiturl', $submiturl);
                $this->assign('data', $result);
                $this->assign('exam', $exam_result);
                // print_r($result);
                $this->display();
            }
            else {
                echo "<script>alert('数据库请求失败');</script>";
                echo "<script>window.history.back(-1);</script>";
            }
        }
        else {
            echo "<script>alert('请求格式错误');</script>";
            echo "<script>window.history.back(-1);</script>";
        }
    }
}