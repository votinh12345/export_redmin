<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use backend\models\FormTemplate;


/**
 * Site controller
 */
class TemplateController extends Controller
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
                        'actions' => ['index'],
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
    public function actionIndex()
    {
//        $config = yii\helpers\ArrayHelper::merge(
//            require(__DIR__ . '/../../common/config/main.php'),
//            require('./test.php')
//        );  
//        var_dump($config);die('111');
        $formModel = new FormTemplate();
        $request = Yii::$app->request;
        $listFileTemplate = $formModel->getListImageFiles();
        if ($request->isPost) {
            $dataPost = $request->post();
            $formModel->load($dataPost);
            $formModel->template = UploadedFile::getInstance($formModel, 'template');
            $formModel->note_template = UploadedFile::getInstance($formModel, 'note_template');
            if ($formModel->validate()) {
                $formModel->upload();
                Yii::$app->session->setFlash('message', 'upload template success');
                return Yii::$app->response->redirect(['/template/detail/', 'type' => $formModel->type_template]);
            }
        }
        return $this->render('detail', [
            'model' => $formModel,
            'listFileTemplate' => $listFileTemplate
        ]);
    }
}
