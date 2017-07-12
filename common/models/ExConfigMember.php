<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ex_config_member".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $time_start
 * @property double $time_end
 * @property string $name
 * @property string $position_project
 * @property string $created_date
 * @property string $update_date
 */
class ExConfigMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ex_config_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['time_start', 'time_end'], 'number'],
            [['created_date', 'update_date'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['position_project'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'time_start' => 'Time Start',
            'time_end' => 'Time End',
            'name' => 'Name',
            'position_project' => 'Position Project',
            'created_date' => 'Created Date',
            'update_date' => 'Update Date',
        ];
    }
}
