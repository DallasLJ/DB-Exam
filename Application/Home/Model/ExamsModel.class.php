<?php
namespace Home\Model;
use Think\Model;

/**
* 
*/
class ExamsModel extends Model
{
	
	protected $_validate = array(
        array('exam_name', '', '用户名（学号）不能为空！', 0, 'unique', 1), //默认情况下用正则进行验证
    );
}