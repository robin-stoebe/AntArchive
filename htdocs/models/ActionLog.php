<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "action_log".
 *
 * @property int $log_id
 * @property string $uname
 * @property string $id
 * @property string $updated
 * @property string $action
 * @property string $appname
 */
class ActionLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['updated'], 'safe'],
            [['uname'], 'string', 'max' => 40],
            [['id'], 'string', 'max' => 100],
            [['action'], 'string', 'max' => 500],
            [['appname'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'uname' => 'Uname',
            'id' => 'ID',
            'updated' => 'Updated',
            'action' => 'Action',
            'appname' => 'Appname',
        ];
    }
}
