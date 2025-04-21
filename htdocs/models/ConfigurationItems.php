<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configurationItems".
 *
 * @property int $id
 * @property string $field
 * @property string $description
 */
class ConfigurationItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configurationItems';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field', 'description'], 'required'],
            [['field'], 'string', 'max' => 25],
            [['description'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'field' => 'Field',
            'description' => 'Description',
        ];
    }
}
