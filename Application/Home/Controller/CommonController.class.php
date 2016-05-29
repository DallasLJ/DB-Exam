<?php 
namespace Home\Controller;
use Think\Controller;

/**
* 
*/
class CommonController extends Controller
{
	public static $userid ='';
	public static $usertype;
	
	public function _initialize()
	{
		# code...
		
		if (!isset($_SESSION['uid'])) {
			# code...
			$this->error('对不起，您还没有登陆', U('Login/login'), 1);
		}
		else {
			$this->userid = session('uid');
			$this->usertype = session('utype');
		}
	}
}