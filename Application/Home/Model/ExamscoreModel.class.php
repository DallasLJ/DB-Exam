<?php
namespace Home\Model;
use Think\Model;
/**
* 
*/
class JudgeModel extends Model
{
	
	protected $_validate = array(
		array('examid', 'require', '题目id为空'),
		array('epid', 'require', '题目id为空'),
		array('answer', 'require', '请输入题目答案'),
	);
}