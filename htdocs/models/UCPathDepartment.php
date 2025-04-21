<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ucpath_departments".
 *
 * @property string $department_id
 * @property string $department
 * @property string $school
 * @property string $ucihomedepartmentcode
 */
class UCPathDepartment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ucpath_departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_id', 'department', 'school', 'ucihomedepartmentcode'], 'required'],
            [['department_id', 'school'], 'string', 'max' => 8],
            [['department'], 'string', 'max' => 50],
            [['ucihomedepartmentcode'], 'string', 'max' => 6],
            [['department_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'department_id' => 'Department ID',
            'department' => 'Department',
            'school' => 'School',
            'ucihomedepartmentcode' => 'Ucihomedepartmentcode',
        ];
    }
}
