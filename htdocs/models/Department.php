<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $id
 * @property string $department
 * @property string $abbrv
 * @property string $academic
 * @property string $updated
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department'], 'string', 'max' => 50],
            [['abbrv'], 'string', 'max' => 8],
            [['academic'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department' => 'Department Name',
            'abbrv'     => 'Abbreviation',
            
        ];
    }


    public function options(){
        $recs = $this->find()->all();
        $options = array();
        foreach($recs as $r){
            $options[$r->id]=$r->department;
        }

        return $options;
    }

    public function findIDByAffiliation($a){
        $parts = explode(':',$a);
        if($dept = Department::find()->where('department = :d',[':d'=>trim($parts[1])])->one()){
            return $dept->id;
        }
        return false;
    }





	public function getIndividuals()
	{
		$dept_id = $this->id;

//echo "\$dept_id = $dept_id";

        return User::find()->where('dept_id = :d', [':d' => $dept_id])->all();

	}

        public function memberOptions(){
            $options =[];
            $all = Department::find()->orderBy('department')->all();
            foreach($all as $d){
                $options['d-'.$d->id]=$d->department;
            }
            return $options;
        }

}
