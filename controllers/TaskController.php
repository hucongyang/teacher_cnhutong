<?php
/**
 * 任务控制器
 */
class TaskController extends ApiPublicController
{
    /**
     * 任务列表接口
     */
    public function actionGetTaskList()
    {
        if (!isset($_REQUEST['teacherId']) || !isset($_REQUEST['token'])) {
            $this->_return('MSG_ERR_LESS_PARAM');
        }

        $user_id = trim(Yii::app()->request->getParam('teacherId'));
        $token = trim(Yii::app()->request->getParam('token'));

        if (!ctype_digit($user_id)) {
            $this->_return('MSG_ERR_FAIL_PARAM');
        }

        // 用户名不存在，返回错误
        if ($user_id < 1) {
            $this->_return('MSG_ERR_NO_USER');
        }

        // 验证token
        if (Token::model()->verifyToken($user_id, $token)) {
            // 获取任务列表
            $data = Task::model()->getTaskList($user_id);
            $this->_return('MSG_SUCCESS', $data);
        } else {
            $this->_return('MSG_ERR_TOKEN');
        }
    }

    /**
     * 获得任务签到接口
     */
    public function actionGetSign()
    {
        if( !isset($_REQUEST['teacherId']) || !isset($_REQUEST['token'])
        || !isset($_REQUEST['lessonDate']) || !isset($_REQUEST['lessonTime'])
        || !isset($_REQUEST['departmentId']) ) {
            $this->_return('MSG_ERR_LESS_PARAM');
        }

        $user_id = trim(Yii::app()->request->getParam('teacherId'));
        $token = trim(Yii::app()->request->getParam('token'));
        $lessonDate = trim(Yii::app()->request->getParam('lessonDate'));
        $lessonTime = trim(Yii::app()->request->getParam('lessonTime'));
        $departmentId = trim(Yii::app()->request->getParam('departmentId'));

        if (!ctype_digit($user_id)) {
            $this->_return('MSG_ERR_FAIL_PARAM');
        }

        // 用户名不存在，返回错误
        if ($user_id < 1) {
            $this->_return('MSG_ERR_NO_USER');
        }

        if (!ctype_digit($departmentId)) {
            $this->_return('MSG_ERR_DEPARTMENT');
        }

        // 验证token
        if (Token::model()->verifyToken($user_id, $token)) {
            // 获取任务签到
            $data = Task::model()->getSign($user_id, $lessonDate, $lessonTime);
            $this->_return('MSG_SUCCESS', $data);
        } else {
            $this->_return('MSG_ERR_TOKEN');
        }
    }

    /**
     * 提交任务签到接口
     */
    public function actionPostSign()
    {
        if (!isset($_REQUEST['teacherId']) || !isset($_REQUEST['token'])
        || !isset($_REQUEST['lessonStudentIds']))
        {
            $this->_return('MSG_ERR_LESS_PARAM');
        }

        $user_id = trim(Yii::app()->request->getParam('teacherId'));
        $token = trim(Yii::app()->request->getParam('token'));
        $lessonStudentIds = trim(Yii::app()->request->getParam('lessonStudentIds'));

        if (!ctype_digit($user_id)) {
            $this->_return('MSG_ERR_FAIL_PARAM');
        }

        // 用户名不存在，返回错误
        if ($user_id < 1) {
            $this->_return('MSG_ERR_NO_USER');
        }

        if (empty($lessonStudentIds) || ($this->isJson($lessonStudentIds))) {
            $this->_return('MSG_ERR_FAIL_LESSONSTUDENTIDS');
        }

        // 解析json，获得课时id和课时step
        $lessonJson = json_decode($lessonStudentIds, true);

//        foreach ($lessonJson as $row) {
//            var_dump($row['lessonStudentId'] . ' + ' . $row['step']);
//        }
//        var_dump($lessonJson);

        // 验证token
        if (Token::model()->verifyToken($user_id, $token)) {
            // 提交任务签到
            $data = Task::model()->postSign($lessonJson);
            if ($data > 0) {
                $this->_return('MSG_SUCCESS');
            } else {
                $this->_return('MSG_ERR_FAIL_LESSONSTUDENTID_STEP');       // 前端数据格式错误，sql 执行错误
            }
        } else {
            $this->_return('MSG_ERR_TOKEN');
        }
    }
}