<?php

/**
 * Class User 有关用户数据模型
 */
class User extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获取用户ID getUserId
     *
     * @param $username             -- 用户名
     * @return int  $user_id        --
     * 说明：根据用户名，获取用户的user_id
     */
    public function getUserId($username)
    {
        $user_id = 0;
        if ($username != null)
        {
            $con_user = Yii::app()->cnhutong;
            if (!$username)
            {
                return $user_id;
            }
            $user_id = 0;
            try {
                $user_id = $con_user->createCommand()
                    ->select('id')
                    ->from('ht_member')
                    ->where('username = :username', array(':username' => $username))
                    ->queryScalar();
            } catch (Exception $e) {
                error_log($e);
            }
        }
        return $user_id;
    }

    /**
     * 获取用户信息 getUserSafeInfo
     * @param $user_id int              -- 用户ID
     * @return array|bool               -- 用户信息
     */
    public function getUserInfo($user_id)
    {
        $user_id = intval($user_id);

        $data = array();
        $table_name = 'ht_member';
        try {
            $con_user = Yii::app()->cnhutong;
            $data = $con_user->createCommand()
                ->select('id, username, password, name, gender, department_id, title, mobile, email, token')
                ->from($table_name)
                ->where('id = :id', array(':id' => $user_id))
                ->queryRow();
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
        return $data;
    }
}