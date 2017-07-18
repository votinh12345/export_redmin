<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Model;
use yii\data\SqlDataProvider;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Style_Color;
use ZipArchive;
/**
 * Login form
 */
class FormExport extends Model
{
    public $sql_single_project;
    public $sql_multiple_project;
    public $sql;
    public $template;



    public function rules()
    {
        return [
            [['sql'], 'required'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sql_single_project' => 'Sql Single Project',
            'sql_multiple_project' => 'Sql Multiple Project',
            'sql' => 'SQL',
            'template' => 'Template'
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
        return ['sql_single_project', 'sql_multiple_project', 'sql', 'template'];
        
    }
    
    /*
     * Get all data detail
     * 
     * Auth : HienNV6244
     * Created : 11-07-2017
     */
    public function getAllDataDetail($flag = true, $flagReturn = true) {
        if ($flag) {
            $query = "SELECT `time_entries`.*, `issues`.`subject`, SUM(`time_entries`.`hours`) AS sum_hours, `users`.`firstname`, `users`.`lastname`, CONCAT(`users`.`lastname`,' ',`users`.`firstname`) AS full_name";
            $query .= " FROM `time_entries`";
            $query .= " INNER JOIN `users` ON users.id = time_entries.user_id";
            $query .= " INNER JOIN `issues` ON issues.id = time_entries.issue_id";
            $query .= " INNER JOIN `enumerations` ON enumerations.id = time_entries.activity_id";
            $query .= " GROUP BY";
            $query .= "`time_entries`.`spent_on`,";
            $query .= "`users`.`firstname`,";
            $query .= "`users`.`lastname`";
            $query .= " ORDER BY";
            $query .= "`time_entries`.`spent_on` DESC";
        } else {
            $query = str_replace("&#39;", "'", html_entity_decode(strip_tags($this->sql)));
            $query = str_replace("&#039;", "'", $query);
            $query = str_replace("/\s+/", " ", $query);
            $query = trim(preg_replace('/(\t|\n|\v|\f|\r| |\xC2\x85|\xc2\xa0|\xe1\xa0\x8e|\xe2\x80[\x80-\x8D]|\xe2\x80\xa8|\xe2\x80\xa9|\xe2\x80\xaF|\xe2\x81\x9f|\xe2\x81\xa0|\xe3\x80\x80|\xef\xbb\xbf)+/', ' ', $query));
        }
        
        if (!$flagReturn) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand($query);
            return $command->queryAll();
        }
        $count = Yii::$app->db->createCommand($query)->queryScalar();
        
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            //'totalCount' => $count,
            'sort' => [
               'attributes' => [
                    'spent_on' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 10
            ],
        ]);
        return $dataProvider;
    }
    
    /*
     * Export excell
     * 
     * Auth : HienNV
     * Created : 18-07-2017
     */
    public function actionExportExcel(){
        $listData = $this->getAllDataDetail(false, false);
        if (count($listData) > 0) {
            $fileName =  Yii::$app->params['folderReport'] . 'template_d6_'.date('YmdHis').'.xlsx';
            copy('export_file/template_d6.xlsx', $fileName);
            $objPHPExcel = new \PHPExcel();
            $objTpl = PHPExcel_IOFactory::load($fileName);
            $userName = '';
            foreach ($listData as $key => $value) {
//                if ($userName != $value['login']) {
//                    $userName = $value['login'];
//                    $sheet1 = $objTpl->getActiveSheet(3)->copy();
//                    $sheet2 = clone $sheet1;
//                    $sheet_title = $userName;
//                    $sheet2->setTitle($sheet_title);
//                    $objTpl->addSheet($sheet2,4);
//                   unset($sheet2);
//                   
//                   //write data
//                   $objTpl->getActiveSheet(3)->insertNewRowBefore(9,12); 
//                   //$objTpl->getActiveSheet()->duplicateStyle($objTpl->getActiveSheet()->getStyle('A8'), 'A9');
//                   
//                }
                //$objTpl->getActiveSheet(3)->insertNewRowBefore(9,12); 
                $objTpl->getActiveSheet(3)->duplicateStyle($objTpl->getActiveSheet(3)->getStyle('A8'), 'A9');
            }
            $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');
            $objWriter->save($fileName);
        }
    }
}