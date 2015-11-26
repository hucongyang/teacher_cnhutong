<?php
/**
 * UserController 个人中心控制器
 */
class UserController extends ApiPublicController
{
    /**
     * 用户登录接口
     * 用户使用用户名/密码登录
     */
    public function actionLogin()
    {
        if (!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) {
            $this->_return('MSG_ERR_LESS_PARAM');
        }

        $username = trim(Yii::app()->request->getParam('username'));
        $password = trim(Yii::app()->request->getParam('password'));

        $user_id = User::model()->getUserId($username);

        // 用户不存在，返回错误
        if ($user_id < 1)
        {
            $this->_return('MSG_ERR_NO_USER');
        }

        // 获取用户信息
        $user_info = User::model()->getUserInfo($user_id);

        if ($username == $user_info['username']) {
            if ($password == '') {
                $this->_return('MSG_ERR_PASSWORD_ERR');
            }
        }

        $data = array();
        if (strcmp($password, $user_info['password']) == 0) {
            // 返回token值
            $result_token = Token::model()->updateUserToken($user_id);
            $messageFlag  = 1;
            $noticeFlag   = 0;
            if ($result_token) {
                // 登录日志，修改token
                if ($user_info) {
                    $data['teacherId']          = $user_info['id'];
                    $data['token']              = $result_token;
                    $data['messageFlag']        = $messageFlag;
                    $data['noticeFlag']         = $noticeFlag;
                    $this->_return('MSG_SUCCESS', $data);
                } else {
                    $this->_return('MSG_ERR_UNKOWN');
                }
            } else {
                $this->_return('MSG_ERR_UNKOWN');
            }
        } else {
            // 密码错误
            $this->_return('MSG_ERR_PASSWORD_WRONG');
        }
    }

    /**
     * 自动登录接口
     * 用户使用用户ID/token验证登录，token 默认30天有效
     */
    public function actionAutoLogin()
    {
        if (!isset($_REQUEST['teacherId']) || !isset($_REQUEST['token'])) {
            $this->_return('MSG_ERR_LESS_PARAM');
        }

        $user_id = trim(Yii::app()->request->getParam('teacherId'));
        $token = trim(Yii::app()->request->getParam('token'));

        if (!ctype_digit($user_id)) {
            $this->_return('MSG_ERR_FAIL_PARAM');
        }

        // 用户不存在，返回错误
        if ($user_id < 1) {
            $this->_return('MSG_ERR_NO_USER');
        }

        $data = array();
        // 验证token
        if (Token::model()->verifyToken($user_id, $token)) {
            // 返回token值
            $result_token = Token::model()->updateUserToken($user_id);
            $messageFlag  = 1;
            $noticeFlag   = 0;
            if ($result_token) {
                // 写入日志，更新用户信息
                $data['teacherId']          = $user_id;
                $data['token']              = $result_token;
                $data['messageFlag']        = $messageFlag;
                $data['noticeFlag']         = $noticeFlag;
                $this->_return('MSG_SUCCESS', $data);
            } else {
                $this->_return('MSG_ERR_UNKOWN');
            }
        } else {
            $this->_return('MSG_ERR_TOKEN');
        }
    }

    /**
     * 退出账号接口
     */
    public function actionLogout()
    {
        if (!isset($_REQUEST['teacherId']) || !isset($_REQUEST['token'])) {
            $this->_return('MSG_ERR_LESS_PARAM');
        }

        $user_id = trim(Yii::app()->request->getParam('teacherId'));
        $token = trim(Yii::app()->request->getParam('token'));

        if (!ctype_digit($user_id)) {
            $this->_return('MSG_ERR_FAIL_PARAM');
        }

        // 用户不存在，返回错误
        if ($user_id < 1) {
            $this->_return('MSG_ERR_NO_USER');
        }

        // 验证token
        if (Token::model()->verifyToken($user_id, $token)) {
            // 指定token过期
            if (Token::model()->expireToken($user_id)) {
                // 退出不写LOG
                $this->_return('MSG_SUCCESS');
            }
        } else {
            $this->_return('MSG_ERR_TOKEN');
        }
    }
}