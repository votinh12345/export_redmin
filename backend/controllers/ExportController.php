<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Projects;
use backend\models\FormReport;
use common\models\Enumerations;
use backend\models\FormExport;
use backend\models\FormTemplate;

/**
 * Site controller
 */
class ExportController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'single'],
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
    public function actions() {
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
    public function actionIndex() {
        $formModelTemplate = new FormTemplate();
        $modelFormExprort = new FormExport();
        $sql_single = file_get_contents('template/sql_single.txt', true);
        $sql_multiple = file_get_contents('template/sql_multiple.txt', true);
        $modelFormExprort->sql_single_project = $sql_single;
        $modelFormExprort->sql_multiple_project = $sql_multiple;
        $request = Yii::$app->request;

        $dataProvider = $modelFormExprort->getAllDataDetail();
        $listFileTemplate = $formModelTemplate->getListImageFiles();
        
        $param = $request->queryParams;
        if (isset($param['view'])) {
            $modelFormExprort->setAttributes($param['FormExport']);
            $dataProvider = $modelFormExprort->getAllDataDetail(false);
        } else if (isset($param['export'])) {
            $modelFormExprort->setAttributes($param['FormExport']);
            $modelFormExprort->actionExportExcel();
            $dataProvider = $modelFormExprort->getAllDataDetail(false);
        }
        return $this->render('index', [
                'modelFormExprort' => $modelFormExprort,
                'dataProvider' => $dataProvider,
                'listFileTemplate' => $listFileTemplate
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionSingle($id) {
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

        return $this->render('single', [
                    'formModelReport' => $formModelReport,
                    'listUserByProject' => $listUserByProject,
                    'projectsItem' => $ProjectsItem,
                    'listActivity' => $listActivity,
                    'dataProvider' => $dataProvider
        ]);
    }

}
