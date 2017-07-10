<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "email_addresses".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $address
 * @property integer $is_default
 * @property integer $notify
 * @property string $created_on
 * @property string $updated_on
 */
class EmailAddresses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_addresses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'address', 'created_on', 'updated_on'], 'required'],
            [['user_id', 'is_default', 'notify'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['address'], 'string', 'max' => 255],
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
            'address' => 'Address',
            'is_default' => 'Is Default',
            'notify' => 'Notify',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
        ];
    }
}
