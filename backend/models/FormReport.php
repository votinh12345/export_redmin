<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Members;
use common\models\Enumerations;

/**
 * Login form
 */
class FormReport extends Model
{
    
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
        'ld' => 'yesterday',
        'w' => 'this week',
        'lw' => 'last week',
        'l2w' => 'last 2 weeks',
        'w' => 'this month',
        'm' => 'last month',
        'y' => 'this year',
        '!*' => 'none',
        '*' => 'any',
    ];
    
    public static $FILTER_USER = [
        '=' => 'is',
        '!' => 'is not',
        '!*' => 'none',
        '*' => 'any',
    ];
    
    public static $FILTER_ACTIVITY = [
        '=' => 'is',
        '!' => 'is not'
    ];
    
    public static $FILTER_COMMENT = [
        '~' => 'contains',
        '!~' => 'doesn`t contain',
        '!*' => 'none',
        '*' => 'any',
    ];
    
    public static $FILTER_HOURS = [
        '=' => 'is',
        '>=' => '>=',
        '<=' => '<=',
        '><' => 'between',
        '!*' => 'none',
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
            'cb_hours' => 'Hours'
        ];
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
    
    /*
     * Get Activity filter
     * 
     * Auth : Hiennv6244
     * Created : 10-07-2017
     */
    
}