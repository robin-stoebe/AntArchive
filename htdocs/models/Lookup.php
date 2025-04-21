<?php

namespace app\models;
use app\models\LookupType;

use Yii;

/**
 * This is the model class for table "lookup".
 *
 * @property int $lookup_id
 * @property int $type_id
 * @property string $description
 * @property int $weight
 */
class Lookup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lookup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'weight'], 'integer'],
            [['description'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lookup_id' => 'Lookup ID',
            'type_id' => 'Type ID',
            'description' => 'Description',
            'weight' => 'Weight',
        ];
    }



    public static function LookupOptions($type,$fields=array())
	{
		$lookupid = LookupType::find()->where("name = :type",[':type'=>$type])->one();
                return Lookup::LookupChoicesOfType($lookupid->lookup_type_id,$fields);
                
		
	}




    public static function LookupChoicesOfType($type_id,$f=['lookup_id','description'])
	{
		$lookups = Lookup::find()->where('type_id = '. $type_id)->orderBy('weight, description')->all();
		return yii\helpers\ArrayHelper::map($lookups, $f[0], $f[1]);
	}



}
