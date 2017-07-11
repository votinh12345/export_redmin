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
//        var_dump(date('Y-m-d', strtotime("-1 day")));die;
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
        //var_dump($param);
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
