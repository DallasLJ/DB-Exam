<?php
return array(
	//'配置项'=>'配置值'

	//Trace Setting
	'SHOW_PAGE_TRACE' => true, 

	//Jump Setting
	'TMPL_ACTION_ERROR' => MODULE_PATH.'View/Public/error.html',
	'TMPL_ACTION_SUCCESS' => MODULE_PATH.'View/Public/success.html',

	//MySQL Setting
	'DB_TYPE' => 'mysql',
	'DB_HOST' => 'localhost',
	'DB_NAME' => 'exam_system',
	'DB_USER' => 'root',
	'DB_PWD' => '135246',
	'DB_PORT' => 3306,
	'DB_PREFIX' => 'think_',
	'DB_CHARSET' => 'utf8',
	'DB_DEBUG' => TRUE,

	//Router Setting
	'URL_ROUTER_ON'   => true,
	'URL_ROUTE_RULES'=>array(
    	'Exam/setproblem/id/:id' => 'Exam/setproblem',
    	'Exam/listproblem/eid/:eid' => 'Exam/listproblem',
    	'Problem/showproblem/pid/:pid' => 'Problem/showproblem',
    	'Exam/showexam/eid/:eid' => 'Exam/showexam',
    	'Exam/showexamproblem/epid/:epid' => 'Exam/showexamproblem',
    	'Examstatus/examstatus/eid/:eid' => 'Examstatus/examstatus',
    	'Exam/deleteexamproblem/eid/:eid/order/:order' => 'Exam/deleteexamproblem',
    	'Problem/update/pid/:pid' => 'Problem/update',
    ),
);