<?php
namespace Home\Model;
use Think\Model;

/**
* 
*/
class LoginModel extends Model
{
	
	protected $tableName = 'users';

	protected $_validate = array(
		array('userid', 'require', '用户名不能为空'), 
		array('password', 'require', '密码不能为空'),
	);

}