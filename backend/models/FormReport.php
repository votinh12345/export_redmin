<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use common\models\Members;
use common\models\Enumerations;
use common\components\Utility;

/**
 * Login form
 */
class FormReport extends Model
{
    public $project_id;
    public $check_spent_on;
    public $spent_on;
    public $values_spent_on_1;
    public $values_spent_on_2;
    public $values_spent_on;


    public $cb_user_id;
    public $filter_user_id;
    public $user_id;
    public $cb_activity_id;
    public $filter_activity_id;
    public $activity_id;
    public $cb_comments;
    public $filter_cb_comments;
    public $comments;
    public $cb_hours;
    public $filter_cb_hours;
    public $hours;
    public $values_hours_1;
    public $values_hours_2;

    public static $FILTER_DATE = [
        '=' => 'is',
        '>=' => '>=',
        '<=' => '<=',
        '><' => 'between',
        '>t-' => 'less than days ago',
        '<t-' => 'more than days ago',
        '><t-' => 'in the past',
        't-' => 'days ago',
        't' => 'to day',
        'ld' => 'yesterday',
        'w' => 'this week',
        'lw' => 'last week',
        'l2w' => 'last 2 weeks',
        'm' => 'this month',
        'lm' => 'last month',
        'y' => 'this year',
//        '!*' => 'none',
        '*' => 'any',
    ];
    
    public static $FILTER_USER = [
        '=' => 'is',
        '!' => 'is not',
//        '!*' => 'none',
        '*' => 'any',
    ];
    
    public static $FILTER_ACTIVITY = [
        '=' => 'is',
        '!' => 'is not'
    ];
    
    public static $FILTER_COMMENT = [
        '~' => 'contains',
        '!~' => 'doesn`t contain',
//        '!*' => 'none',
        '*' => 'any',
    ];
    
    public static $FILTER_HOURS = [
        '=' => 'is',
        '>=' => '>=',
        '<=' => '<=',
        '><' => 'between',
//        '!*' => 'none',
        '*' => 'any',
    ];
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'check_spent_on' => 'Date',
            'cb_user_id' => 'User',
            'cb_activity_id' => 'Activity',
            'cb_comments' => 'Comment',
            'cb_hours' => 'Hours',
            'project_id' => 'Project Id'
        ];
    }
    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values, $safeOnly);
    }
    
    /**
     * @inheritdoc
     */
    public function safeAttributes()
    {
        $safe = parent::safeAttributes();
        return array_merge($safe, $this->extraFields());
    }
    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['check_spent_on', 'spent_on' , 'values_spent_on_1', 'values_spent_on_2', 'values_spent_on',
            'cb_user_id', 'filter_user_id', 'user_id', 'cb_activity_id', 'filter_activity_id', 'activity_id',
            'cb_comments', 'filter_cb_comments', 'comments', 'cb_hours', 'filter_cb_hours', 'hours', 'values_hours_1',
            'values_hours_2', 'project_id'];
        
    }
    
    /*
     * Get list user by project
     * 
     * Auth : Hiennv6244
     * Created : 10-07-2017
     */
    
    public function getListUserByProject($projectID) {
        $query = new \yii\db\Query();
        $query->select(['users.*'])
                ->from('members');
        $query->join('INNER JOIN', 'users', 'users.id = members.user_id');
        $query->where(['=', 'members.project_id', $projectID]);
        $query->andWhere(['=', 'users.status', Members::STATUS_ACTIVE]);
        return $query->all();
    }
    
    /*
     * List user by project
     * 
     * Auth : Hiennv6244
     * Created : 10-07-2017
     */
    public function listUserByProject ($projectID) {
        $result = [];
        $listUser = $this->getListUserByProject($projectID);
        if (count($listUser) > 0) {
            foreach ($listUser as $key => $value) {
                $result[$value['id']] = $value['lastname'] . ' ' . $value['firstname'];
            }
        }
        return $result;
    }
    
    public function isShowSpentOn1() {
        if ($this->check_spent_on == 0 || ($this->check_spent_on == 1 && !in_array($this->spent_on, ['=', '>=', '<=', '><']))) {
            return false;
        }
        return true;
    }
    
    public function isShowSpentOn2() {
        if ($this->check_spent_on == 0 || ($this->check_spent_on == 1 && $this->spent_on != '><')) {
            return false;
        }
        return true;
    }
    
    public function isShowSpentOn() {
        if ($this->check_spent_on == 0 || ($this->check_spent_on == 1 && !in_array($this->spent_on, ['>t-', '<t-', '><t-', 't-']))) {
            return false;
        }
        return true;
    }
    
    public function isShowHours1() {
        if ($this->cb_hours == 0 || ($this->cb_hours == 1 && !in_array($this->filter_cb_hours, ['=', '>=', '<=', '><']))) {
            return false;
        }
        return true;
    }
    
    public function isShowHours2() {
        if ($this->cb_hours == 0 || ($this->cb_hours == 1 && $this->filter_cb_hours != '><')) {
            return false;
        }
        return true;
    }
    
    /*
     * Check show button export excel by member
     * 
     * Auth : HienNv6244
     * Created : 12-07-2017
     */
    public function isShowButtonExportByMember() {
        $flagShow = false;
        if ($this->check_spent_on == 1) {
            switch ($this->spent_on) {
                case '><':
                    $month1 = date("m",strtotime($this->values_spent_on_1));
                    $month2 = date("m",strtotime($this->values_spent_on_2));
                    if ($month1 == $month2) {
                        $flagShow = true;
                    }
                    break;
                case 'w':
                    $date = Utility::getDate($this->spent_on);
                    $flagShow = $this->checkDateTwoDay($date);
                    break;
                case 'lw':
                    $date = Utility::getDate($this->spent_on);
                    $flagShow = $this->checkDateTwoDay($date);
                    break;
                case 'l2w':
                    $date = Utility::getDate($this->spent_on);
                    $flagShow = $this->checkDateTwoDay($date);
                    break;
                case 'm':
                    $flagShow = true;
                    break;
                case 'lm':
                    $flagShow = true;
                    break;
                default :
                    break;
            }
        }
        
        return $flagShow;
    }

    /*
     * Check show button export excel report month
     * 
     * Auth : HienNv6244
     * Created : 12-07-2017
     */
    public function isShowButtonExportReportMonth() {
        $flagShow = false;
        if ($this->check_spent_on == 1) {
            switch ($this->spent_on) {
                case '><':
                    $month1 = date("m",strtotime($this->values_spent_on_1));
                    $month2 = date("m",strtotime($this->values_spent_on_2));
                    if ($month1 == $month2) {
                        $flagShow = true;
                    }
                    break;
                case 'm':
                    $flagShow = true;
                    break;
                case 'lm':
                    $flagShow = true;
                    break;
                default :
                    break;
            }
        }
        
        return $flagShow;
    }
    
    public function checkDateTwoDay($date) {
        $flagShow = false;
        $month1 = date("m",strtotime($date['firstDate']));
        $month2 = date("m",strtotime($date['lastDate']));
        if ($month1 == $month2) {
            $flagShow = true;
        }
        return $flagShow;
    }
    /*
     * Get all data detail
     * 
     * Auth : HienNV6244
     * Created : 11-07-2017
     */
    public function getAllDataDetail($projectId) {
        $query = new \yii\db\Query();
        $query->select(['time_entries.*', 'users.firstname', 'users.lastname', 'enumerations.name as name_activity', 'issues.subject'])
                ->from('time_entries');
        $query->join('INNER JOIN', 'users', 'users.id = time_entries.user_id');
        $query->join('INNER JOIN', 'issues', 'issues.id = time_entries.issue_id');
        $query->join('INNER JOIN', 'enumerations', 'enumerations.id = time_entries.activity_id');
        $query->andFilterWhere(['=', 'time_entries.project_id' , $projectId]);
        if ($this->check_spent_on == 1) {
            switch ($this->spent_on) {
                case '=':
                    $query->andFilterWhere(['=', 'time_entries.spent_on' , $this->values_spent_on_1]);
                    break;
                case '>=':
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $this->values_spent_on_1]);
                    break;
                case '<=':
                    $query->andFilterWhere(['<=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $this->values_spent_on_1]);
                    break;
                case '><':
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $this->values_spent_on_1]);
                    $query->andFilterWhere(['<=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $this->values_spent_on_2]);
                    break;
                case '>t-':
                    //$query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , date('Y-m-d', strtotime("-".$this->values_spent_on." day"))]);
                    break;
                case '<t-':
                    //$query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , date('Y-m-d', strtotime("-".$this->values_spent_on." day"))]);
                    break;
                case '><t-':
                    //$query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , date('Y-m-d', strtotime("-".$this->values_spent_on." day"))]);
                    break;
                case 't-':
                    //$query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , date('Y-m-d', strtotime("-".$this->values_spent_on." day"))]);
                    break;
                case 't':
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , date('Y-m-d', strtotime(date("Y-m-d H:i:s")))]);
                    break;
                case 'ld':
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , date('Y-m-d', strtotime('-1 day'))]);
                    break;
                case 'w':
                    $date = Utility::getDate($this->spent_on);
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['firstDate']]);
                    $query->andFilterWhere(['<=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['lastDate']]);
                    break;
                case 'lw':
                    $date = Utility::getDate($this->spent_on);
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['firstDate']]);
                    $query->andFilterWhere(['<=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['lastDate']]);
                    break;
                case 'l2w':
                    $date = Utility::getDate($this->spent_on);
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['firstDate']]);
                    $query->andFilterWhere(['<=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['lastDate']]);
                    break;
                case 'm':
                    $date = Utility::getDate($this->spent_on);
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['firstDate']]);
                    $query->andFilterWhere(['<=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['lastDate']]);
                    break;
                case 'lm':
                    $date = Utility::getDate($this->spent_on);
                    $query->andFilterWhere(['>=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['firstDate']]);
                    $query->andFilterWhere(['<=', 'DATE_FORMAT(time_entries.spent_on,"%Y-%m-%d")' , $date['lastDate']]);
                    break;
                case '!*':
                    
                    break;
                default:
                    break;
            }
        }
        //add filter for user
        if ($this->cb_user_id == 1) {
            switch ($this->filter_user_id) {
                case '=':
                    $query->andFilterWhere(['IN', 'users.id' , $this->user_id]);
                    break;
                case '!':
                    $query->andFilterWhere(['NOT IN', 'users.id' , $this->user_id]);
                    break;
                default :
                    break;
            }
        }
        //add filter for activity
        if ($this->cb_activity_id == 1) {
            switch ($this->filter_activity_id) {
                case '=':
                    $query->andFilterWhere(['IN', 'time_entries.activity_id' , $this->activity_id]);
                    break;
                case '!':
                    $query->andFilterWhere(['NOT IN', 'time_entries.activity_id' , $this->activity_id]);
                    break;
                default :
                    break;
            }
        }
        
        //add filter for Comment
        if ($this->cb_comments == 1) {
            switch ($this->filter_cb_comments) {
                case '~':
                    $query->andFilterWhere(['LIKE', 'time_entries.comments' , $this->comments]);
                    break;
                case '!~':
                    $query->andFilterWhere(['OR LIKE', 'time_entries.comments' , $this->comments]);
                    break;
                default :
                    break;
            }
        }
        
        //add filter for hours
        if ($this->cb_hours == 1) {
            switch ($this->filter_cb_hours) {
                case '=':
                    $query->andFilterWhere(['=', 'time_entries.hours' , $this->values_hours_1]);
                    break;
                case '>=':
                    $query->andFilterWhere(['>=', 'time_entries.hours' , $this->values_hours_1]);
                    break;
                case '<=':
                    $query->andFilterWhere(['<=', 'time_entries.hours' , $this->values_hours_1]);
                    break;
                case '><':
                    $query->andFilterWhere(['>=', 'time_entries.hours' , $this->values_hours_1]);
                    $query->andFilterWhere(['<=', 'time_entries.hours' , $this->values_hours_2]);
                    break;
                default :
                    break;
            }
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize']
            ],
            'sort' => [
                'defaultOrder' => [
                    'spent_on' => SORT_DESC
                ]
            ],
        ]);
        
        $dataProvider->sort->attributes['spent_on'] = [
            'desc' => ['time_entries.spent_on' => SORT_DESC],
            'asc' => ['time_entries.spent_on' => SORT_ASC],
        ];
        return $dataProvider;
    }
}