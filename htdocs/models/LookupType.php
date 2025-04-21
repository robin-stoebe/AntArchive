<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_type".
 *
 * @property int $lookup_type_id
 * @property string $name
 * @property string $sort_direction
 */
class LookupType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lookup_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'sort_direction'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lookup_type_id' => 'Lookup Type ID',
            'name' => 'Name',
            'sort_direction' => 'Sort Direction',
        ];
    }
    
    
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $lookupItems=$this->getLookupItems();
       
        foreach($lookupItems as $l)
            $l->delete();
        
        // ...custom code here...
        return true;
    }    
    
   public function getLookupItems()
    {
        return $this->hasMany(Lookup::className(), ['type_id' => 'lookup_type_id'])->all();
    }
    
    
}
