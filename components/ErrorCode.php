<?php
/*********************************************************
 * 错误码列表
 * 
 * @author  Lujia
 * @version 1.0 by Lujia @ 2013.12.23 创建错误列表
 ***********************************************************/
 
$_error_code = array(
		// 基本错误码
		'MSG_SUCCESS' 				        => array('10000', '成功'),
		'MSG_ERR_LESS_PARAM' 		        => array('10001', '请求缺少必要的参数'),
		'MSG_ERR_FAIL_PARAM' 		        => array('10002', '请求参数错误'),


	    // 用户相关错误码
        'MSG_ERR_NO_USER'                   => array('20001', '用户不存在'),
        'MSG_ERR_PASSWORD_ERR'              => array('20002', '系统用户，不能登录'),
        'MSG_ERR_PASSWORD_WRONG'            => array('20003', '您输入的密码错误'),
        'MSG_ERR_TOKEN'                     => array('20004', 'TOKEN验证错误'),
        'MSG_ERR_DEPARTMENT'                => array('20005', '校区不存在'),
		'MSG_ERR_FAIL_LESSONSTUDENTIDS'		=> array('20006', '课时参数错误'),
		'MSG_ERR_FAIL_LESSON_FORMAT'		=> array('20007', '课时格式错误'),
		'MSG_ERR_FAIL_LESSONDETAILS'		=> array('20008', '课时详情参数错误'),
		'MSG_ERR_FAIL_DATE_FORMAT'			=> array('20009', '日期格式错误'),
		'MSG_ERR_FAIL_DATE_LESS'			=> array('20010', '缺少必要的日期内容'),


		// 其它
		'MSG_ERR_FAIL_SQL'					=> array('88888', 'SQL执行错误'),
		'MSG_ERR_UNKOWN'			=> array('99999', '系统繁忙，请稍后再试')
);

// return $ErrorCode;
