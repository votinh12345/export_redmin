<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $login
 * @property string $hashed_password
 * @property string $firstname
 * @property string $lastname
 * @property integer $admin
 * @property integer $status
 * @property string $last_login_on
 * @property string $language
 * @property integer $auth_source_id
 * @property string $created_on
 * @property string $updated_on
 * @property string $type
 * @property string $identity_url
 * @property string $mail_notification
 * @property string $salt
 * @property integer $must_change_passwd
 * @property string $passwd_changed_on
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin', 'status', 'auth_source_id', 'must_change_passwd'], 'integer'],
            [['last_login_on', 'created_on', 'updated_on', 'passwd_changed_on'], 'safe'],
            [['login', 'lastname', 'type', 'identity_url', 'mail_notification'], 'string', 'max' => 255],
            [['hashed_password'], 'string', 'max' => 40],
            [['firstname'], 'string', 'max' => 30],
            [['language'], 'string', 'max' => 5],
            [['salt'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'hashed_password' => 'Hashed Password',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'admin' => 'Admin',
            'status' => 'Status',
            'last_login_on' => 'Last Login On',
            'language' => 'Language',
            'auth_source_id' => 'Auth Source ID',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'type' => 'Type',
            'identity_url' => 'Identity Url',
            'mail_notification' => 'Mail Notification',
            'salt' => 'Salt',
            'must_change_passwd' => 'Must Change Passwd',
            'passwd_changed_on' => 'Passwd Changed On',
        ];
    }
}
