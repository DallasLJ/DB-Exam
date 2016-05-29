<?php
namespace Home\Controller;
use Think\Controller;

/**
* 
*/
class StatusController extends CommonController
{
	
	public function status() {
		$where_flag = false;
		$where = array();
		if ($_GET['author']) {
			$where['b.userid'] = array('LIKE','%'. I('get.author'). '%');
			$where_flag = true;
		}
		if ($_GET['pid']) {
			$where['a.pid'] = I('get.pid');
			$where_flag = true;
		}

		$judge = M('judge');

		if ($where_flag) {
			$count = $judge->alias('a')->where($where)->join('think_users b ON a.uid = b.id')->count();
		}
		else {
			$count = $judge->count();
		}
		
		$Page = new \Think\Page($count,10);

		$Page->lastSuffix = false;

		$Page->setConfig('header', $count.'篇记录');
		$Page->setConfig('prev', '上一页');
		$Page->setConfig('next', '下一页');
		$Page->setConfig('first', '首页');
		$Page->setConfig('last', '末页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

		$show = $Page->show();
		if ($where_flag) {
			$list = $judge->alias('a')->field('a.*, b.username, b.userid')->join('think_users b ON a.uid = b.id')->order('id desc')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		else {
			$list = $judge->alias('a')->field('a.*, b.username, b.userid')->join('think_users b ON a.uid = b.id')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	public function personalstatus() {
		$judge = M('judge');

		$where = array();
		$where['uid'] = session('id');

		$count = $judge->where($where)->count();
		$Page = new \Think\Page($count,25);

		$Page->lastSuffix = false;

		$Page->setConfig('header', $count.'篇记录');
		$Page->setConfig('prev', '上一页');
		$Page->setConfig('next', '下一页');
		$Page->setConfig('first', '首页');
		$Page->setConfig('last', '末页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');

		$show = $Page->show();
		$list = $judge->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}
}