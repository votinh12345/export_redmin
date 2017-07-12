<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Projects;
use backend\models\FormReport;
use common\models\Enumerations;

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
                $formModelReport->actionExportExcelByMember($id);
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
}
