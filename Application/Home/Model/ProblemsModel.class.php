<?php
namespace Home\Model;
use Think\Model;

/**
* 
*/
class ProblemsModel extends Model
{
	
	protected $_validate = array(
		array('typeid', 'require', '请输入题目类型'),
		array('title', 'require', '请输入题目名'),
		array('content', 'require', '请输入题目描述'),
		array('answer', 'require', '请输入题目分数'),
		array('score', 'require', '请输入题目分数'),

	);
}