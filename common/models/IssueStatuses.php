<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "issue_statuses".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_closed
 * @property integer $position
 * @property integer $default_done_ratio
 */
class IssueStatuses extends \yii\db\ActiveRecord
{
    const ISSUE_STATUS_NEW = 1;
    const ISSUE_STATUS_IN_PROGRESS = 2;
    const ISSUE_STATUS_RESOLVED = 3;
    const ISSUE_STATUS_CLOSED = 5;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue_statuses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_closed', 'position', 'default_done_ratio'], 'integer'],
            [['name'], 'string', 'max' => 30],
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
            'is_closed' => 'Is Closed',
            'position' => 'Position',
            'default_done_ratio' => 'Default Done Ratio',
        ];
    }
}
