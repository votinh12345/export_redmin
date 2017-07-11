<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "time_entries".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $issue_id
 * @property double $hours
 * @property string $comments
 * @property integer $activity_id
 * @property string $spent_on
 * @property integer $tyear
 * @property integer $tmonth
 * @property integer $tweek
 * @property string $created_on
 * @property string $updated_on
 */
class TimeEntries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'time_entries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'hours', 'activity_id', 'spent_on', 'tyear', 'tmonth', 'tweek', 'created_on', 'updated_on'], 'required'],
            [['project_id', 'user_id', 'issue_id', 'activity_id', 'tyear', 'tmonth', 'tweek'], 'integer'],
            [['hours'], 'number'],
            [['spent_on', 'created_on', 'updated_on'], 'safe'],
            [['comments'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'user_id' => 'User ID',
            'issue_id' => 'Issue ID',
            'hours' => 'Hours',
            'comments' => 'Comments',
            'activity_id' => 'Activity ID',
            'spent_on' => 'Spent On',
            'tyear' => 'Tyear',
            'tmonth' => 'Tmonth',
            'tweek' => 'Tweek',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
        ];
    }
}
