<?php
namespace Home\Model;
use Think\Model;

/**
* 
*/
class ExamproblemModel extends Model
{
	
	protected $_validate = array(
		array('examid', 'require', '请输入题目类型'),
		array('pid', 'require', '请输入题目名'),
		array('problem_order', 'require', '请输入题目描述'),
		array('submit_times', 'require', '请输入题目分数'),
	);
}