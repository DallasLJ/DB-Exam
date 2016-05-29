<?php
namespace Home\Model;
use Think\Model;
/**
* 
*/
class JudgeModel extends Model
{
	
	protected $_validate = array(
		array('pid', 'require', '题目id为空'),
		array('answer', 'require', '请输入题目答案'),
	);
}