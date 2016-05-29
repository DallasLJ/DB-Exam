<?php
namespace Home\Controller;
use Think\Controller;
/**
* 
*/
class ExamstatusController extends CommonController
{
	public function examscore() {
		$examid = $_GET['eid'];
		if ($examid) {
			$model = new \Think\Model();
			$sqlqurey = 'SELECT tmp.uid, user.userid, user.username, sum(mscore) as scoresum FROM (Select uid, epid, max(score) as mscore from think_examscore where examid=' . $examid . ' group by uid, epid) tmp left join think_users user on tmp.uid = user.id group by uid order by scoresum desc, uid;';
			$result = $model->query($sqlqurey);

			$exam = D('exams');
			$where = array();
			$where['id'] = $examid;
			$result_exam = $exam->where($where)->find();

			if ($result && $result_exam) {
				$this->assign('data', $result);
				$this->assign('exam', $result_exam);
				$this->display();
			}
			else {
				$this->error('请求成绩不存在');
			}
		}
		else {
			$this->error('请求格式出错');
		}		
	}

	public function personalstatus() {
		$uid = session('id');
		$score = D('examscore');
		$where = array();
		$where['uid'] = $uid;
		
		$count = $score->where($where)->count();
		$Page = new \Think\Page($count,25);

		$Page->lastSuffix = false;

		$Page->setConfig('header', $count.'篇记录');
		$Page->setConfig('prev', '上一页');
		$Page->setConfig('next', '下一页');
		$Page->setConfig('first', '首页');
		$Page->setConfig('last', '末页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

		$show = $Page->show();
		$list = $score->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
}