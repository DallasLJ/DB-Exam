<?php
namespace Home\Controller;
use Think\Controller;
/**
* 
*/
class AdminController extends CommonController
{
	
	public function administration() {
        $where_flag = false;
        $where = array();
        if ($_GET['account']) {
            $where['userid'] = array('LIKE', '%'. I('get.account'). '%');
            $where_flag = true;
        }
        if ($_GET['user_name']) {
            $where['username'] = array('LIKE', '%'. I('get.user_name'). '%');
            $where_flag = true;
        }
        if ($_GET['user_type']) {
            $where['usertype'] = I('get.user_type');
            $where_flag = true;
        }
        if ($_GET['user_status']) {
            $where['status'] = I('get.user_status');
            $where_flag = true;
        }

    	$user = M('users');
        if ($where_flag) {
            $count = $user->where($where)->count();
        }
        else {
    	   $count = $user->count();
        }

		$Page = new \Think\Page($count,25);

		$Page->lastSuffix = false;

		$Page->setConfig('header', $count.'篇记录');
		$Page->setConfig('prev', '上一页');
		$Page->setConfig('next', '下一页');
		$Page->setConfig('first', '首页');
		$Page->setConfig('last', '末页');
		$Page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

		$show = $Page->show();
        if ($where_flag) {
            $list = $user->where($where)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        }
        else {
            $list = $user->order('id')->limit($Page->firstRow.','.$Page->listRows)->select();
        }

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
    }

    public function changestatus() {
    	if ($_GET['uid']) {
    		$uid = I('get.uid');
    		$user = M('users');
    		$dosql = 'update think_users set status = status XOR 1 where id='. $uid. ';';
    		$count = $user->execute($dosql);
    		$rt_data = array();
    		if ($count) {
    			$rt_data['status'] = 1;
                $this->ajaxReturn($rt_data);
    		}
    		else {
    			$rt_data['status'] = 1;
                $this->ajaxReturn($rt_data);
    		}
    	}
    }

    public function upusertype() {
    	if ($_GET['uid']) {
    		$uid = I('get.uid');
    		$user = M('users');
    		$dosql = 'update think_users set usertype = usertype+1 where id='. $uid. ' and usertype<3;';
    		$count = $user->execute($dosql);
    		$rt_data = array();
    		if ($count) {
    			$rt_data['status'] = 1;
                $this->ajaxReturn($rt_data);
    		}
    		else {
    			$rt_data['status'] = 0;
                $this->ajaxReturn($rt_data);
    		}
    	}
    }

    public function downusertype() {
    	if ($_GET['uid']) {
    		$uid = I('get.uid');
    		$user = M('users');
    		$dosql = 'update think_users set usertype = usertype-1 where id='. $uid. ' and usertype>1;';
    		$count = $user->execute($dosql);
    		$rt_data = array();
    		if ($count) {
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