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
        $listFileTemplate = $formModelTemplate->getListImageFiles(false);
        
        $param = $request->queryParams;
        if (isset($param['view'])) {
            $modelFormExprort->setAttributes($param['FormExport']);
            $dataProvider = $modelFormExprort->getAllDataDetail(false);
        } else if (isset($param['export'])) {
            $modelFormExprort->setAttributes($param['FormExport']);
            $modelFormExprort->actionExportExcel();
        }
        return $this->render('index', [
                'modelFormExprort' => $modelFormExprort,
                'dataProvider' => $dataProvider,
                'listFileTemplate' => $listFileTemplate
        ]);
    }
}
