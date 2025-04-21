<?php

namespace app\models;

use Yii;
use app\models\ConfigurationItem;
/**
 * This is the model class for table "configuration".
 *
 * @property int $id
 * @property string $item
 * @property string $value
 * @property string $description
 */
class Configuration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item'], 'required'],
            [['item'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 1500],
            [['description'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item' => 'Item',
            'value' => 'Value',
            'description' => 'Description',
        ];
    }
    
    
        public function items(){
            $items =[];
            $all = ConfigurationItem::find()->all();
            foreach($all as $item){
                $items[$item->field]=$item->description;
            }
            
            return $items;
        }


    
	/**
	 * find the specified configuration item for a division.
	 * return the item or false if not set
	 * @param string $item
	 * @param integer $division
	 * @return string/boolean
	 */
	public function configValue($item)
	{  
            global $debug;
            
		if($setting = Configuration::find()->where(['item'=>$item])->one()){
                    \app\components\Debug::debug("FOUND config with item $item:: ".print_r($setting->getAttributes(),true),'debug of configValue');
		
			return $setting->value;
                } else 	 {
                    \app\components\Debug::debug("did not find config with item $item;",'debug of configValue');
			return false;
                }
	}
	
	public function debugMode(){
                global $debug;
		$setting = Configuration::configValue('debug');
               
		if(!empty($setting) or $debug>=1)
		
			return true;
		else
			return false;
		
	}    
    
}
