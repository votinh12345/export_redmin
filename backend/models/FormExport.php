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
use PHPExcel_Worksheet;
use yii\base\ErrorException;
use yii\helpers\FileHelper;
use common\components\Utility;
/**
 * Login form
 */
class FormExport extends Model {

    public $sql_single_project;
    public $sql_multiple_project;
    public $sql;
    public $template;

    public function rules() {
        return [
            [['sql', 'template'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sql_single_project' => 'Sql Single Project',
            'sql_multiple_project' => 'Sql Multiple Project',
            'sql' => 'SQL',
            'template' => 'Template'
        ];
    }

    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
    }

    /**
     * @inheritdoc
     */
    public function safeAttributes() {
        $safe = parent::safeAttributes();
        return array_merge($safe, $this->extraFields());
    }

    /**
     * @inheritdoc
     */
    public function extraFields() {
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
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
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

    public function actionExportExcel() {
        $listData = $this->getAllDataDetail(false, false);
        if (count($listData) > 0) {
            //check map file config and sql
            if (!$this->checkSql()) {
                Yii::$app->session->setFlash('message', 'Field config and file return SQL not map!');
                return Yii::$app->response->redirect(['/export/index']);
            }
            //copy file or copy sheet
            $fileConfig = Yii::$app->params['folder_template'] . $this->template . '.php';
            $configExport = yii\helpers\ArrayHelper::merge(
                [],
                require($fileConfig)
            );
            $nameCopy = $this->copyFileOrSheet($listData, $configExport);
            
            //insert data 
            $this->insertDataToFile($listData, $configExport, $nameCopy);
            
            //download file
            $this->downloadFile($configExport, $nameCopy);
            
            return true;
        }
    }
    
    public function listTotalRecordByMember($listData) {
        $data = [];
        $listMember = [];
        $member = '';
        
        if (count($listData) > 0) {
            foreach ($listData as $key => $value) {
                if (!in_array($value['login'], $listMember)) {
                    $listMember[] = $value['login'];
                }
            }
            foreach ($listMember as $key => $value) {
                $i = 0;
                foreach ($listData as $key1 => $value1) {
                    if ($value1['login'] == $value) {
                        $i++;
                    }
                }
                $data[$value] = $i;
            }
        }
        return $data;
    }
    
    /*
     * Check file config and sql data return
     * 
     * Auth : Hiennv6244
     * Created : 24-07-2017
     */
    public function checkSql() {
        $listData = $this->getAllDataDetail(false, false);
        if (count($listData) == 0) {
            return TRUE;
        }
        $fileConfig = Yii::$app->params['folder_template'] . $this->template . '.php';
        if (!file_exists($fileConfig)) {
            return FALSE;
        }
        $configExport = yii\helpers\ArrayHelper::merge(
            [],
            require($fileConfig)
        );
        if (empty($configExport['filed_export'])) {
            return FALSE;
        }
        $keySql = array_keys($listData[0]);
        $arrayDiff = array_diff($configExport['filed_export'], $keySql);
        if (count($arrayDiff) > 0) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /*
     * Auth : Hiennv6244
     * Created : 24-07-2017
     */
    public function copyFileOrSheet($listData, $configExport) {
        $fileTemplate = Yii::$app->params['folder_template'] . $this->template . '.xlsx';
        if (!file_exists($fileTemplate)) {
            return FALSE;
        }
        $fileNameCopy = $this->template. '_' . date('YmdHis');
        $typeCopy = $configExport['table_main']['type_copy'];
        switch ($typeCopy) {
            case 0:
                $fileName = Yii::$app->params['folderReport'] . $fileNameCopy . '.xlsx';
                copy($fileTemplate, $fileName);
                break;
            case 1 :
                //created folder
                Utility::createdFolder($fileNameCopy);
                foreach ($listData as $key => $value) {
                    $fileName = Yii::$app->params['folderReport'] . $fileNameCopy . '/' .$this->template. '_' . $value[$configExport['table_main']['multiple']] . '.xlsx';
                    copy($fileTemplate, $fileName);
                }
                break;
            case 2 :
                $fileName = Yii::$app->params['folderReport'] . $fileNameCopy . '.xlsx';
                copy($fileTemplate, $fileName);
                //clone sheet
                $objPHPExcel = new \PHPExcel();
                $objTpl = PHPExcel_IOFactory::load($fileName);
                $userName = '';
                foreach ($listData as $key => $value) {
                    if ($userName != $value[$configExport['table_main']['multiple']]) {
                        $userName = $value[$configExport['table_main']['multiple']];
                        $sheet1 = $objTpl->getActiveSheet($configExport['table_main']['base_sheet'])->copy();
                        $sheet2 = clone $sheet1;
                        $sheet_title = $userName;
                        $sheet2->setTitle($sheet_title);
                        $objTpl->addSheet($sheet2,4);
                        unset($sheet2);
                    }
                }
                //save file excel
                $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');
                $objWriter->save($fileName);
                unset($objWriter);
                break;
            default:
                break;
        }
        return $fileNameCopy;
    }
    
    /*
     * insert data
     * 
     * Auth : Hiennv6244
     * Created : 24-07-2017
     */
    public function insertDataToFile($listData, $configExport, $nameCopy) {
        $typeCopy = $configExport['table_main']['type_copy'];
        switch ($typeCopy) {
            case 0:
                break;
            case 1 :
                //created folder zip
                $compress = new ZipArchive();
                $compressFolder = Yii::$app->params['folderReport'] . $nameCopy . '.zip';
                $compress->open($compressFolder, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                $userName = '';
                foreach ($listData as $key => $value) {
                    $objPHPExcel = new \PHPExcel();
                    if ($userName != $value[$configExport['table_main']['multiple']]) {
                        $userName = $value[$configExport['table_main']['multiple']];
                        $fileName = Yii::$app->params['folderReport'] . $nameCopy . '/' .$this->template. '_' . $value[$configExport['table_main']['multiple']] . '.xlsx';
                        $objTpl = PHPExcel_IOFactory::load($fileName);
                        $objTpl->setActiveSheetIndex(0);
                    }
                    //set value row row_special
                    if ($configExport['table_main']['row_special'] && count($configExport['table_main']['row_special']) > 0) {
                        foreach ($configExport['table_main']['row_special'] as $key1 => $value1) {
                            if ($value1['flag_db'] == 0) {
                                $objTpl->getActiveSheet()->setCellValue($value1['position'], $value1['value']);
                            } else {
                                $objTpl->getActiveSheet()->setCellValue($value1['position'], $value[$value1['value']]);
                            }
                        }
                    }
                    //insert row
                    if ($configExport['cells'] && count($configExport['cells']) > 0) {
                        foreach ($configExport['cells'] as $key1 => $value1) {
                            $row = (int)date("d", strtotime($value[$value1['value_relation']]));
                            $rowWrite = $value1['startRow'] + $row;
                            $objTpl->getActiveSheet()->setCellValue($value1['position'].$rowWrite, $value[$value1['value']]);
                        }
                    }
                    $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');
                    $objWriter->save($fileName);
                    $compress->addFile($fileName);
                }
                $compress->close();
                unset($objWriter);
                break;
            case 2 :
                $dataTotalByMember = $this->listTotalRecordByMember($listData);
                $fileName = Yii::$app->params['folderReport'] . $nameCopy . '.xlsx';
                $objPHPExcel = new \PHPExcel();
                $objTpl = PHPExcel_IOFactory::load($fileName);
                $i = $j = $k = 0;
                $userName = $userName1 = $userName2 = '';
                $listRecordAccount = [];
                $starDataRow = 8;
                //insert sheet Summary
                $objTpl->setActiveSheetIndexByName('Summary');
                foreach ($dataTotalByMember as $key => $value) {
                    $objTpl->getActiveSheet()->setCellValue('B'.($starDataRow + $k), $key);
                    $k++;
                }
                //insert data member of sheet
                foreach ($listData as $key => $value) {
                    if ($userName1 != $value['login']) {
                        $userName1 = $value['login'];
                        //set active sheet
                        $objTpl->setActiveSheetIndexByName($userName1);
                        $i = 0;
                    }
                    $i++;
                    $objTpl->getActiveSheet()->insertNewRowBefore($starDataRow + $i, 1);
                    $objTpl->getActiveSheet()->duplicateStyle($objTpl->getActiveSheet()->getStyle('A' . $starDataRow), 'A' . ($starDataRow + 1));
                    $objTpl->getActiveSheet()->mergeCells('B'. ($starDataRow + $i) . ':D' . ($starDataRow + $i));
                    $objTpl->getActiveSheet()->mergeCells('E'. ($starDataRow + $i) .':H' . ($starDataRow + $i));
                    $objTpl->getActiveSheet()->mergeCells('I'. ($starDataRow + $i) .':J' . ($starDataRow + $i));
                    $objTpl->getActiveSheet()->setCellValue('A' . ($starDataRow + $i - 1), $value['name']);
                    $objTpl->getActiveSheet()->setCellValue('B' . ($starDataRow + $i - 1), $value['subject']);
                    $objTpl->getActiveSheet()->setCellValue('I' . ($starDataRow + $i - 1), '=SUM(K'. ($starDataRow + $i - 1) .':AO' . ($starDataRow + $i - 1) . ')');
                    $day = (int)date("d", strtotime($value['spent_on']));
                    $objTpl->getActiveSheet()->setCellValue(Yii::$app->params['day'][$day] . ($starDataRow + $i - 1), $value['hours']);

                }
                //insert project
                foreach ($listData as $key => $value) {
                    if ($userName2 != $value['login']) {
                        $j = 0;
                        $projectName = '';
                        $userName2 = $value['login'];
                        $totalData = $dataTotalByMember[$userName2];
                        //set active sheet
                        $objTpl->setActiveSheetIndexByName($userName2);
                    }
                    //insert project
                    if ($projectName != $value['name']) {
                        $j++;
                        $projectName = $value['name'];
                        $rowProject = $totalData + $starDataRow + $j + 4;
                        $objTpl->getActiveSheet()->setCellValue('E'.$rowProject, $projectName);
                        for ($i = 1; $i <= 31; $i++) {
                            $rowFomular = $totalData + $starDataRow + 1;
                            $objTpl->getActiveSheet()->setCellValue(Yii::$app->params['day'][$i].$rowProject, '=SUMIFS('.Yii::$app->params['day'][$i].'$' . $starDataRow . ':'.Yii::$app->params['day'][$i].'$'. $rowFomular. ',$A$'.$starDataRow.':$A$'.$rowFomular.',$E'.$rowProject.')');
                        }
                    }
                }
                //save file excel
                $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');
                $objWriter->save($fileName);
                unset($objWriter);
                break;
            default:
                break;
        }
        
        return true;
    }
    
    /*
     * Download file
     * 
     * Auth : Hiennv6244
     * Created : 24-07-2017
     */
    public function downloadFile($configExport, $nameCopy){
        $typeCopy = $configExport['table_main']['type_copy'];
        switch ($typeCopy) {
            case 0 :
                break;
            case 1 :
                $compressFolder = Yii::$app->params['folderReport'] . $nameCopy . '.zip';
                header("Content-type: application/zip"); 
                header("Content-Disposition: attachment; filename=$compressFolder"); 
                header("Pragma: no-cache"); 
                header("Expires: 0"); 
                readfile("$compressFolder");
                break;
            case 2 :
                $fileName = Yii::$app->params['folderReport'] . $nameCopy . '.xlsx';
                header("Content-type: application/xlsx"); 
                header("Content-Disposition: attachment; filename=$fileName"); 
                header("Pragma: no-cache"); 
                header("Expires: 0"); 
                readfile("$fileName");
                break;
            default :
                break;
        }
        return true;
    }
}
