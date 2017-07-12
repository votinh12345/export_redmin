<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use common\models\Members;
use common\components\Utility;
use common\models\Projects;
use common\models\ExConfigMember;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
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
    
    public function getListUserByProject($projectID, $flag = true) {
        $query = new \yii\db\Query();
        $query->select(['users.*'])
                ->from('members');
        $query->join('INNER JOIN', 'users', 'users.id = members.user_id');
        $query->where(['=', 'members.project_id', $projectID]);
        $query->andWhere(['=', 'users.status', Members::STATUS_ACTIVE]);
        if ($this->cb_user_id == 1 && $this->filter_user_id == '!' && !$flag) {
            $query->andWhere(['NOT IN', 'users.id', $this->user_id]);
        }
        return $query->all();
    }
    
    /*
     * List user by project
     * 
     * Auth : Hiennv6244
     * Created : 10-07-2017
     */
    public function listUserByProject ($projectID, $flag = true) {
        $result = [];
        $listUser = $this->getListUserByProject($projectID);
        if (!$flag) {
            $listUser = $this->getListUserByProject($projectID, false);
        }
        if (count($listUser) > 0) {
            if ($flag) {
                foreach ($listUser as $key => $value) {
                    $result[$value['id']] = $value['lastname'] . ' ' . $value['firstname'];
                }
            } else {
                foreach ($listUser as $key => $value) {
                    $result[] = $value['id'];
                }
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
    public function isShowButtonExportByMember($flagReturnDate = true) {
        $flagShow = false;
        $result = [];
        if ($this->check_spent_on == 1) {
            switch ($this->spent_on) {
                case '><':
                    $month = $month1 = date("m",strtotime($this->values_spent_on_1));
                    $month2 = date("m",strtotime($this->values_spent_on_2));
                    $year = date("Y",strtotime($this->values_spent_on_1));
                    if ($month1 == $month2) {
                        $flagShow = true;
                    }
                    $result = [
                        'month' => $month,
                        'year' => $year
                    ];
                    break;
                case 'w':
                    $date = Utility::getDate($this->spent_on);
                    $result = $this->checkDateTwoDay($date, true);
                    $flagShow = $this->checkDateTwoDay($date);
                    break;
                case 'lw':
                    $date = Utility::getDate($this->spent_on);
                    $result = $this->checkDateTwoDay($date, true);
                    $flagShow = $this->checkDateTwoDay($date);
                    break;
                case 'l2w':
                    $date = Utility::getDate($this->spent_on);
                    $result = $this->checkDateTwoDay($date, true);
                    $flagShow = $this->checkDateTwoDay($date);
                    break;
                case 'm':
                    $month = date("m",strtotime(date('Y-m-d')));
                    $year = date("Y",strtotime(date('Y-m-d')));
                    $result = [
                        'month' => $month,
                        'year' => $year
                    ];
                    $flagShow = true;
                    break;
                case 'lm':
                    $date = date('Y-m-d', strtotime('-1 month'));
                    $month = date("m",strtotime($date));
                    $year = date("Y",strtotime($date));
                    $result = [
                        'month' => $month,
                        'year' => $year
                    ];
                    $flagShow = true;
                    break;
                default :
                    break;
            }
        }
        if (!$flagReturnDate) {
            return $result;
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
    
    public function checkDateTwoDay($date, $flag = false) {
        $flagShow = false;
        $month1 = date("m",strtotime($date['firstDate']));
        $year1 = date("Y",strtotime($date['firstDate']));
        $month2 = date("m",strtotime($date['lastDate']));
        $year2 = date("Y",strtotime($date['lastDate']));
        if ($month1 == $month2 && $year1 = $year2) {
            $flagShow = true;
        }
        if ($flag) {
            $result = [
                'month' => $month1,
                'year' => $year1
            ];
        }
        return $flagShow;
    }
    /*
     * Get all data detail
     * 
     * Auth : HienNV6244
     * Created : 11-07-2017
     */
    public function getAllDataDetail($projectId, $userId = NULL, $flagReturn = true) {
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
        if ($this->cb_user_id == 1 && $flagReturn) {
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
        //add filter for one member
        if (!$flagReturn) {
            $query->andFilterWhere(['IN', 'time_entries.user_id' , $userId]);
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
        if (!$flagReturn) {
            $query->groupBy(['time_entries.spent_on']);
            return $query->all();
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
    
    /*
     * Export report member
     * 
     * Auth : HienNV6244
     * Created : 12-07-2017
     */
    public function actionExportExcelByMember($projectId) {
        if ($this->cb_user_id == 0 || ($this->cb_user_id == 1 && $this->filter_user_id == '*')) {
            $listUser = $this->listUserByProject($projectId, false);
        } elseif ($this->cb_user_id == 1 && $this->filter_user_id == '=') {
            $listUser = $this->user_id;
        } elseif ($this->cb_user_id == 1 && $this->filter_user_id == '!') {
            $listUser = $this->listUserByProject($projectId, false);
        }
        if (count($listUser) == 0) {
            exit;
        }
        //created folder
        $project = Projects::find()->select('identifier')->where(['id' => $projectId])->one();
        $nameProject = $project['identifier'] . '_' . date('YmdHis');
        Utility::createdFolder($nameProject);
        $yearAndDate = $this->isShowButtonExportByMember(false);
        foreach ($listUser as $key => $value) {
            $fileName = '作業報告書_'.Yii::$app->params['position_default'].'(' . Yii::$app->params['name_default'] .')_' . $yearAndDate['year'] . $yearAndDate['month'] . '.xlsx';
            $user = ExConfigMember::find()->where(['user_id' => $value])->one();
            if ($user) {
                $fileName = '作業報告書_' . $user->name . '(' . $user->position_project .')_' . $yearAndDate['year'] . $yearAndDate['month'] . '.xlsx';
            }
             //copy file default from file sample
            copy('export_file/' . $project['identifier'] . '_template.xlsx', Yii::$app->params['folderReport'] . $nameProject . '/' . $fileName);
            
            $objPHPExcel = new \PHPExcel();
            $tmpfname = Yii::$app->params['folderReport'] . $nameProject . '/' . $fileName;
            
            $objTpl = PHPExcel_IOFactory::load($tmpfname);
            $objTpl->setActiveSheetIndex(0);
            //set date for J9
            $date = $yearAndDate['year'] . '-' . $yearAndDate['month'] . '01';
            $lastDate = date("m/t/Y", strtotime($date));
            $objTpl->getActiveSheet()->setCellValue('J9', $lastDate);
            //$objTpl->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //C1 is right-justified
            //set data for C5
            $dataC5 = $yearAndDate['month'] . '月度 作業報告書（兼納品書）';
            $objTpl->getActiveSheet()->setCellValue('C5', $dataC5);
            
            //set data for J9
            $dataUser = \common\models\User::find()->where(['id' => $value])->one();
            $dataJ9 = $dataUser->firstname . ' ' . $dataUser->lastname;
            $objTpl->getActiveSheet()->setCellValue('J9', $dataJ9);
            
            //get all data
            $data = $this->getAllDataDetail($projectId, $value, FALSE);
            if (count($data) > 0) {
                foreach ($data as $key => $value) {
                    
                }
            }
            
//            $objTpl->getActiveSheet()->getStyle('C4')->getAlignment()->setWrapText(true);  //set wrapped for some long text message
//
//            $objTpl->getActiveSheet()->getColumnDimension('C')->setWidth(40);  //set column C width
//            $objTpl->getActiveSheet()->getRowDimension('4')->setRowHeight(120);  //set row 4 height
//            $objTpl->getActiveSheet()->getStyle('A4:C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); //A4 until C4 is vertically top-aligned


            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');  //downloadable file is in Excel 2003 format (.xls)
            $objWriter->save(Yii::$app->params['folderReport'] . $nameProject . '/' . $fileName);  //send it to user, of course you can save it to disk also!

            exit;
        }
    }
    
}