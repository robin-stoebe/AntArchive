<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $log_id
 * @property string $when
 * @property int $user_id
 * @property string $type
 * @property string $data
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['when'], 'safe'],
            [['username', 'type', 'data'], 'required'],
            [['data','username'], 'string'],
            [['username'], 'string', 'max' => 60],
            [['type'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'when' => 'When',
            'username' => 'User ID',
            'type' => 'Type',
            'data' => 'Data',
        ];
    }
    
    public function entry($t,$data='')
        {
        	
            if(isset(Yii::$app->user) and !Yii::$app->user->isGuest)
                $user = Yii::$app->user->identity->username;
            else
                    $user="nouser";
        	
            $log = new \app\models\Log;
            $log->username = $user;
            $log->type=$t;
            $log->data=print_r($data,true);
            $log->when=date('Y-m-d H:i:s');

            if(!($log->validate() && $log->save()))
            {
                print_r([$t,$data,$log->getErrors()]);
                 Yii::log('Error Saving log'.print_r($log->getErrors(),TRUE), 'error', 'review2');
                 throw new  CHttpException('Writing to Log');

            }
        }    


        public function cronEntry($t,$data)
        {
        	
            $user="cron";
        	
            $log = new \app\models\Log;
            $log->username = $user;
            $log->type=$t;
            $log->data=$data;
            $log->when=date('Y-m-d H:i:s');

            if(!($log->validate() && $log->save()))
            {
                print_r([$t,$data,$log->getErrors()]);
                 Yii::log('Error Saving log'.print_r($log->getErrors(),TRUE), 'error', 'review2');
                 throw new  CHttpException('Writing to Log');

            }
        }    





        public function timestampOfLastEntry($type)
        {
            // to get the latest we have to use "orderBy" with "all()" at the end  (and "limit(1)" to get just the one record)

            $last_array = Log::find()
                                ->where(['type' => $type])
                                ->orderBy(['when' => SORT_DESC])
                                ->limit(1)
                                ->all();

            // find() with all()  returns an array of records rather than an object, so we just want the one object
            $last = $last_array[0];

            
                //  need to investigate whether there's a better way to do this 

            return $last->when;
        }    



}
