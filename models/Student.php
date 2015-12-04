<?php
/**
 * 学员数据模型
 */
class Student extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获取学员列表:
     * 默认显示该教师所授课程的所有学生,可以根据条件进行筛选搜索
     * @param $user_id
     * @param $search
     * @param $subjectId
     * @param $departmentId
     * @param $studentId
     * @return array
     */
    public function getAllStudents($user_id, $search, $subjectId, $departmentId, $studentId)
    {
        $data = array();
        try {
            $con_student = Yii::app()->cnhutong;

            if ($studentId == 0) {
                $studentIdWhere = null;
            } else {
                $studentIdWhere = ' AND cd.student_id < "' . addslashes($studentId) . '"';
            }

            $sql = "SELECT cd.student_id AS studentId, m.`name` AS studentName,
                      c.subject_id AS subjectId, c.`subject` as subjectName,
                      cd.department_id AS departmentId, d.`name` AS departmentName,
                      sum(cd.lesson_finished_cnt) AS hasSerial, cd.lesson_cnt AS cotSerial,
                      ifnull(max(cd.end_date), '') AS date
                    FROM ht_contract_detail cd
                      LEFT JOIN ht_member m ON m.id = cd.student_id
                      LEFT JOIN ht_course c ON c.id = cd.course_id
                      LEFT JOIN ht_department d ON d.id = cd.department_id
                    WHERE cd.step >= 0 AND cd.teacher_id = '" . $user_id . "'
                      AND m.`name` LIKE '" . "%" . $search . "%" . "'
                      AND c.subject_id = '" . $subjectId . "'
                      AND cd.department_id = '" . $departmentId . "'
                      " . $studentIdWhere . "
                    GROUP BY cd.student_id
                    ORDER BY cd.student_id DESC
                    LIMIT 10";

            $command = $con_student->createCommand($sql)->queryAll();
//            var_dump($sql);
//            var_dump($command);
            $data['students'] = $command;
        } catch (Exception $e) {
            error_log($e);
        }
        return $data;
    }
}