<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "enumerations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $position
 * @property integer $is_default
 * @property string $type
 * @property integer $active
 * @property integer $project_id
 * @property integer $parent_id
 * @property string $position_name
 */
class Enumerations extends \yii\db\ActiveRecord
{
    const TYPE_TIME_ENTRY_ACTIVITY = 'TimeEntryActivity';
    const IS_ACTIVE = 1;
    const NOT_ACTIVE = 0;
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'enumerations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position', 'is_default', 'active', 'project_id', 'parent_id'], 'integer'],
            [['name', 'position_name'], 'string', 'max' => 30],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'position' => 'Position',
            'is_default' => 'Is Default',
            'type' => 'Type',
            'active' => 'Active',
            'project_id' => 'Project ID',
            'parent_id' => 'Parent ID',
            'position_name' => 'Position Name',
        ];
    }
    
    /*
     * Get list activity
     * 
     * Auth : Hiennv6244
     * Created : 10-07-2017
     */
    
    public function getListActitivty() {
        return Enumerations::find()->select('name')->where(['type' => self::TYPE_TIME_ENTRY_ACTIVITY, 'active' => self::IS_ACTIVE, 'project_id' => NULL])->orderBy(['position' => SORT_ASC])->indexBy('id')->column();
    }
}
