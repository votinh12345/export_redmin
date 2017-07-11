<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "issues".
 *
 * @property integer $id
 * @property integer $tracker_id
 * @property integer $project_id
 * @property string $subject
 * @property string $description
 * @property string $due_date
 * @property integer $category_id
 * @property integer $status_id
 * @property integer $assigned_to_id
 * @property integer $priority_id
 * @property integer $fixed_version_id
 * @property integer $author_id
 * @property integer $lock_version
 * @property string $created_on
 * @property string $updated_on
 * @property string $start_date
 * @property integer $done_ratio
 * @property double $estimated_hours
 * @property integer $parent_id
 * @property integer $root_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $is_private
 * @property string $closed_on
 */
class Issues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issues';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tracker_id', 'project_id', 'status_id', 'priority_id', 'author_id'], 'required'],
            [['tracker_id', 'project_id', 'category_id', 'status_id', 'assigned_to_id', 'priority_id', 'fixed_version_id', 'author_id', 'lock_version', 'done_ratio', 'parent_id', 'root_id', 'lft', 'rgt', 'is_private'], 'integer'],
            [['description'], 'string'],
            [['due_date', 'created_on', 'updated_on', 'start_date', 'closed_on'], 'safe'],
            [['estimated_hours'], 'number'],
            [['subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tracker_id' => 'Tracker ID',
            'project_id' => 'Project ID',
            'subject' => 'Subject',
            'description' => 'Description',
            'due_date' => 'Due Date',
            'category_id' => 'Category ID',
            'status_id' => 'Status ID',
            'assigned_to_id' => 'Assigned To ID',
            'priority_id' => 'Priority ID',
            'fixed_version_id' => 'Fixed Version ID',
            'author_id' => 'Author ID',
            'lock_version' => 'Lock Version',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'start_date' => 'Start Date',
            'done_ratio' => 'Done Ratio',
            'estimated_hours' => 'Estimated Hours',
            'parent_id' => 'Parent ID',
            'root_id' => 'Root ID',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'is_private' => 'Is Private',
            'closed_on' => 'Closed On',
        ];
    }
}
