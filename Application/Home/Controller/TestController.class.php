<?php
namespace Home\Controller;
use Think\Controller;
/**
* 
*/
class TestController extends Controller
{
	
	public function hello()
	{
		# code...
		$user = M("users");
		$list = $user->select();
		// $list = array(
		// 	array('id' => '1', 'name' => '51php'),
		// 	array('id' => '2', 'name' => 'IP2'),
		// );
		$this->assign('list', $list);
		$this->display();
	}
}