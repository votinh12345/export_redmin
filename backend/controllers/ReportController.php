<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Projects;
use backend\models\FormReport;
use common\models\Members;
use common\models\Enumerations;
use common\components\Utility;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
/**
 * Site controller
 */
class ReportController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['detail'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionDetail($id)
    {
        

//        //prepare download
//        $filename= mt_rand(1,100000).'.xlsx'; //just some random filename
//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment;filename="'.$filename.'"');
//        header('Cache-Control: max-age=0');
////
//        $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');  //downloadable file is in Excel 2003 format (.xls)
//        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
////
//        exit; //done.. exiting!
////        
        
        $request = Yii::$app->request;
        $modelProjects = new Projects();
        $modelEnumerations = new Enumerations();
        $formModelReport = new FormReport();
        
        $ProjectsItem = $modelProjects->find()->where(['id' => $id])->one();
        if (empty($ProjectsItem)) {
            return Yii::$app->response->redirect(['/site/error']);
        }
        $param = $request->queryParams;
        //set value filter before search
        if (empty($param['FormReport'])) {
            $formModelReport->check_spent_on = 1;
            $formModelReport->cb_user_id = $formModelReport->cb_activity_id = $formModelReport->cb_comments = $formModelReport->cb_hours = 0;
            $formModelReport->spent_on = '*';
            $formModelReport->filter_user_id = '=';
            $formModelReport->filter_activity_id = '=';
            $formModelReport->filter_cb_comments = '~';
            $formModelReport->filter_cb_hours = '*';
        } else {
            $formModelReport->setAttributes($param['FormReport']);
        }
        
        if ($request->isPost) {
            $post = $request->post();
            if (!empty($post['export-member'])) {
                $this->actionExportExcelByMember($id);
            }
        }
        //get list filter
        $listUserByProject = $formModelReport->listUserByProject($ProjectsItem->id);
        $listActivity = $modelEnumerations->getListActitivty();
        
        $dataProvider = $formModelReport->getAllDataDetail($id);
        
        return $this->render('detail', [
            'formModelReport' => $formModelReport,
            'listUserByProject' => $listUserByProject,
            'projectsItem' => $ProjectsItem,
            'listActivity' => $listActivity,
            'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionExportExcelByMember($projectId) {
        //created folder
        $project = Projects::find()->select('identifier')->where(['id' => $projectId])->one();
        $nameProject = $project['identifier'] . '_' . date('YmdHis');
        Utility::createdFolder($nameProject);
        //copy file default from file sample
        copy('export_file/aeon_mobile_template.xlsx', Yii::$app->params['folderReport'] . $nameProject . '/' . 'test.xlsx');
        
        $objPHPExcel = new \PHPExcel();
        $tmpfname = Yii::$app->params['folderReport'] . $nameProject . '/' . 'test.xlsx';
        
        $objTpl = PHPExcel_IOFactory::load($tmpfname);
        $objTpl->setActiveSheetIndex(0);  //set first sheet as active
        
        
        $objTpl->getActiveSheet()->setCellValue('L1', date('Y-m-d'));  //set C1 to current date
        $objTpl->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); //C1 is right-justified

        $objTpl->getActiveSheet()->setCellValue('C3', stripslashes('2121'));
        $objTpl->getActiveSheet()->setCellValue('C4', stripslashes('dddddd'));

        $objTpl->getActiveSheet()->getStyle('C4')->getAlignment()->setWrapText(true);  //set wrapped for some long text message

        $objTpl->getActiveSheet()->getColumnDimension('C')->setWidth(40);  //set column C width
        $objTpl->getActiveSheet()->getRowDimension('4')->setRowHeight(120);  //set row 4 height
        $objTpl->getActiveSheet()->getStyle('A4:C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP); //A4 until C4 is vertically top-aligned

        //prepare download
        $filename=mt_rand(1,100000).'.xlsx'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007');  //downloadable file is in Excel 2003 format (.xls)
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!

        exit;
    }
}
