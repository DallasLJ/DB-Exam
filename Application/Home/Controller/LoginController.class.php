<?php
namespace Home\Controller;
use Think\Controller;
/**
* 
*/
class LoginController extends Controller
{
	
	public function login(){
		if (IS_POST) {
			$login = D('login');

			if (!$data = $login->create()) {
				# code...
				header("Content-type: text/html; charset=utf-8");
				exit($login->getError());
			}

			$where = array();
			$where['userid'] = $data['userid'];
			$where['password'] = $data['password'];
			$result = $login->where($where)->find();

			if ($result && $result['status']) {
				$savedata = array();
				$savedata['lastlogintime'] = date("Y-m-d H:i:s");
				session('id', $result['id']);
				session('uid', $result['userid']);
				session('username', $result['username']);
				session('utype', $result['usertype']);

				$where['userid'] = session('uid');
                M('users')->where($where)->setInc('loginnum');
                M('users')->where($where)->save($savedata);

                $this->success('登陆成功', U('Index/index'), 3);
			}
			else if ($result == false) {
				$this->error('登录失败,用户名或密码不正确!', '',3);
			}
			else {
				$this->error('账号未被管理员审核或已被禁用!', '',3);
			}
		}
		else {
			$this->display();
		}
	}

	public function loginout()
	{
		session('id', null);
		session('uid', null);
		session('username', null);
		$this->success('退出成功', U('Login/login'));
	}

	public function register()
    {
        // 判断提交方式 做不同处理
        if (IS_POST) {
            // 实例化User对象
            $user = D('users');
            // 自动验证 创建数据集
            if (!$data = $user->create()) {
                // 防止输出中文乱码
                header("Content-type: text/html; charset=utf-8");
                exit($user->getError());
            }
            $data['accountcreatetime'] = date("Y-m-d H:i:s");
            $data['lastlogintime'] = $data['accountcreatetime'];
            //插入数据库
            if ($user->add($data)) {
                /* 直接注册用户为超级管理员,子用户采用邀请注册的模式,
                   遂设置公司id等于注册用户id,便于管理公司用户*/
                $this->success('注册成功', U('Login/login'), 2);
            } else {
                $this->error('注册失败');
            }
        } else {
            $this->display();
        }
    }
}