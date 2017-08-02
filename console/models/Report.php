<?php

namespace console\models;

use Yii;
use yii\helpers\Url;
use common\models\Users;
use common\models\Trackers;
use common\models\IssueStatuses;
use common\models\Enumerations;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Style_Color;
use ZipArchive;
use PHPExcel_Worksheet;
/**
 * This is the model class for table "members".
 *
 */
class Report extends \yii\db\ActiveRecord
{
    /*
     * get data report
     * 
     * Auth : HienNV6244
     * Created : 28-07-2017
     */
    public function getAllDataReport() {
        $dataConfig = Yii::$app->params['report_config'];
        $listData = [];
        $date = date('Y-m-d');
        $date = '2017-08-01';
        if (date("D", strtotime($date)) == 'Sun' || date("D", strtotime($date)) == 'Sat') {
            return false;
        }
        foreach ($dataConfig as $key => $value) {
            $typeError = $value['value'];
            if ($value['enable']) {
                $lisDataError = $this->getData($typeError, $date);
                if (count($lisDataError) > 0) {
                    foreach ($lisDataError as $key1 => $value1) {
                        if ($typeError == 4) {
                            if ($value1['sum_hours'] == 0 || $value1['sum_hours'] == NULL) {
                                $message = 'Chưa nhập công số ngày '. date('d-m-Y');
                            } elseif ($value1['sum_hours'] < 8) {
                                $message = 'Nhập thiếu '. (8 - $value1['sum_hours']) .' giờ cho ngày '. date('d-m-Y');
                            } else {
                                continue;
                            }
                        } else {
                            $message = $value['message'];
                        }
                        if ($typeError == 1) {
                            if ($value1['sum_hours'] == 0 || $value1['sum_hours'] == NULL) {
                                $listData[$value1['login']][] = [
                                    'name_user' => $value1['full_name'],
                                    'projects_name' => $value1['name'],
                                    'id_issuse' => (!empty($value1['id'])) ? $value1['id'] : '',
                                    'subject_issuse' => (!empty($value1['subject'])) ? $value1['subject'] : '',
                                    'message' => $message
                                ];
                            } else {
                                continue;
                            }
                        }
                        $listData[$value1['login']][] = [
                            'name_user' => $value1['full_name'],
                            'projects_name' => $value1['name'],
                            'id_issuse' => (!empty($value1['id'])) ? $value1['id'] : '',
                            'subject_issuse' => (!empty($value1['subject'])) ? $value1['subject'] : '',
                            'message' => $message
                        ];
                    }
                }
            }
        }
        //write file exel
        if (count($listData) > 0) {
            $fileTemplate = Yii::$app->urlManagerBackend->baseUrl . '/' . Yii::$app->params['folderReport'] . 'report_day.xlsx';
            $fileNameCopy = 'report_day'. '_' . date('YmdHis');
            $fileName = Yii::$app->urlManagerBackend->baseUrl . '/' . Yii::$app->params['folderReport'] . $fileNameCopy . '.xlsx';
            copy($fileTemplate, $fileName);
            $objPHPExcel = new \PHPExcel();
            $objTpl = PHPExcel_IOFactory::load($fileName);
            $l= 2;
            $userName = '';
            foreach ($listData as $key => $value) {
                if ($userName != $key) {
                    $userName = $key;
                    $objTpl->getActiveSheet()->setCellValue('B'.$l, $key);
                    $l = $l + count($value);
                    $k = 0;
                }
                foreach ($value as $key1 => $value1) {
                    $row = $l + $k -count($value);
                    $objTpl->getActiveSheet()->setCellValue('C'.$row, $value1['name_user']);
                    $objTpl->getActiveSheet()->setCellValue('D'.$row, $value1['projects_name']);
                    $objTpl->getActiveSheet()->setCellValue('E'.$row, $value1['id_issuse']);
                    $objTpl->getActiveSheet()->setCellValue('F'.$row, $value1['subject_issuse']);
                    $objTpl->getActiveSheet()->setCellValue('G'.$row, $value1['message']);
                    $k ++;
                }
            }
            //save file excel
            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');
            $objWriter->save($fileName);
            unset($objWriter);
            return $fileName;
        }
        
        return false;
    }
    
    /*
     * get error
     * 
     * Auth : HienNV6244
     * Created : 28-07-2017
     */
    public function getData($typeError, $date) {
        switch ($typeError) {
            case 1:
                //query get all member for project
                $subquery = new \yii\db\Query();
                $subquery->select(["issues.id"])
                        ->from('issues');
                $subquery->where(['IN', 'issues.status_id', [IssueStatuses::ISSUE_STATUS_RESOLVED, IssueStatuses::ISSUE_STATUS_CLOSED]]);
                $subquery->andWhere(['=', 'DATE_FORMAT(issues.updated_on, "%Y-%m-%d")', $date]);
                
                //get data
                $query = new \yii\db\Query();
                $query->select(["users.login", "CONCAT(users.lastname,' ', users.firstname) AS full_name", "projects.name", "issues.id", "issues.subject", "SUM(`time_entries`.`hours`) AS sum_hours"])
                        ->from('time_entries');
                $query->innerJoin('issues', 'issues.id = time_entries.issue_id');
                $query->innerJoin('projects', 'projects.id = time_entries.project_id');
                $query->innerJoin('journals', 'journals.journalized_id = issues.id');
                $query->innerJoin('users', 'users.id = journals.user_id');
                $query->innerJoin('journal_details', 'journals.journalized_id = journal_details.id AND journal_details.prop_key = "status_id" AND (journal_details.value = ' . IssueStatuses::ISSUE_STATUS_RESOLVED . ' OR journal_details.value = '. IssueStatuses::ISSUE_STATUS_CLOSED . ')');
                $query->where(['IN', 'time_entries.issue_id', $subquery]);
                $query->groupBy(['time_entries.issue_id']);
                $query->orderBy(['journals.id' => SORT_DESC]);
                return $query->all();
                break;
            case 2:
                $query = new \yii\db\Query();
                $query->select(["users.login", "CONCAT(users.lastname,' ', users.firstname) AS full_name", "projects.name", "issues.id", "issues.subject"])
                        ->from('issues');
                $query->innerJoin('users', 'issues.author_id = users.id');
                $query->innerJoin('projects', 'projects.id = issues.project_id = projects.id');
                $query->where(['IN', 'issues.project_id', Yii::$app->params['project_id']]);
                $query->andWhere(['=' , 'DATE_FORMAT(issues.created_on,"%Y-%m-%d")', "'" . $date . "'"]);
                $query->andWhere(['=' , 'issues.tracker_id', Trackers::STATUS_BUG]);
                $query->andWhere([
                    'OR',
                    ['IS' , 'issues.description' , NULL],
                    ['=', 'issues.description', '']
                ]);
                return $query->all();
                break;
            case 3:
                $date = date('Y-m-d', strtotime('-1 day'));
                $query = new \yii\db\Query();
                $query->select(["users.login", "CONCAT(users.lastname,' ', users.firstname) AS full_name", "projects.name", "issues.id", "issues.subject"])
                        ->from('issues');
                $query->innerJoin('users', 'issues.author_id = users.id');
                $query->innerJoin('projects', 'projects.id = issues.project_id = projects.id');
                $query->where(['IN', 'issues.project_id', Yii::$app->params['project_id']]);
                $query->andWhere(['=' , 'DATE_FORMAT(issues.due_date,"%Y-%m-%d")', $date]);
                $query->andWhere(['NOT IN' , 'issues.status_id', [IssueStatuses::ISSUE_STATUS_RESOLVED, IssueStatuses::ISSUE_STATUS_CLOSED]]);
                return $query->all();
                break;
            case 4:
                //query get all member for project
                $subquery = new \yii\db\Query();
                $subquery->select(["members.user_id"])
                        ->from('members');
                $subquery->innerJoin('users', 'members.user_id = users.id');
                $subquery->where(['IN', 'members.project_id', Yii::$app->params['project_id']]);
                $subquery->andWhere(['=', 'users.status', Users::STATUS_ACTIVE]);
                $subquery->groupBy(['members.user_id']);
                
                //get data
                $query = new \yii\db\Query();
                $query->select(["users.login", "CONCAT(users.lastname,' ', users.firstname) AS full_name", "projects.name", "SUM(time_entries.hours) AS sum_hours"])
                        ->from('users');
                $query->leftJoin('time_entries', 'users.id = time_entries.user_id AND time_entries.spent_on = "' . $date . '"');
                $query->leftJoin('projects', 'projects.id = time_entries.project_id');
                $query->where(['IN', 'users.id', $subquery]);
                $query->groupBy(['time_entries.spent_on', 'users.firstname', 'users.lastname']);
                return $query->all();
                break;
            case 5:
                //query get all member for project
                $subquery = new \yii\db\Query();
                $subquery->select(["issues.assigned_to_id"])
                        ->from('issues');
                $subquery->innerJoin('users', 'users.id = issues.assigned_to_id');
                $subquery->where(['=' , 'issues.status_id', IssueStatuses::ISSUE_STATUS_IN_PROGRESS]);
                $subquery->andWhere(['IN', 'issues.project_id', Yii::$app->params['project_id']]);
                $subquery->andWhere(['IS NOT', 'issues.assigned_to_id', NULL]);
                $subquery->andWhere(['=', 'users.status', Users::STATUS_ACTIVE]);
                $subquery->groupBy(['issues.assigned_to_id']);
                
                //get data
                $query = new \yii\db\Query();
                $query->select(["users.login", "CONCAT(users.lastname,' ', users.firstname) AS full_name", "projects.name", "issues.id", "issues.subject"])
                        ->from('time_entries');
                $query->innerJoin('users', 'users.id = time_entries.user_id');
                $query->innerJoin('projects', 'projects.id = time_entries.project_id');
                $query->innerJoin('issues', 'issues.id = time_entries.issue_id');
                $query->where(['=', 'time_entries.activity_id', Enumerations::ENUMERATIONS_OTHER]);
                $query->andWhere(['IN', 'time_entries.user_id', $subquery]);
                $query->andWhere(['=', 'time_entries.spent_on', $date]);
                return $query->all();
                break;
            case 6:
                $query = new \yii\db\Query();
                $query->select(["users.login", "CONCAT(users.lastname,' ', users.firstname) AS full_name", "projects.name", "issues.id", "issues.subject"])
                        ->from('issues');
                $query->innerJoin('users', 'issues.author_id = users.id');
                $query->innerJoin('projects', 'projects.id = issues.project_id = projects.id');
                $query->where(['IN', 'issues.project_id', Yii::$app->params['project_id']]);
                $query->andWhere(['=' , 'DATE_FORMAT(issues.start_date,"%Y-%m-%d")', $date]);
                $query->andWhere(['=' , 'issues.status_id', IssueStatuses::ISSUE_STATUS_NEW]);
                return $query->all();
                break;
            default :
                break;
        }
    }
}