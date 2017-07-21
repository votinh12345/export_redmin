<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use backend\models\FormTemplate;
use yii\helpers\FileHelper;

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
                        'actions' => ['index', 'detail'],
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
        $formModel = new FormTemplate();
        $request = Yii::$app->request;
        $listFileTemplate = $formModel->getListImageFiles();
        if ($request->isPost) {
            $dataPost = $request->post();
            $formModel->load($dataPost);
            $formModel->template = UploadedFile::getInstance($formModel, 'template');
            $formModel->note_template = UploadedFile::getInstance($formModel, 'note_template');
            $formModel->scenario = FormTemplate::SCENARIO_ADD;
            if ($formModel->validate()) {
                $formModel->upload();
                Yii::$app->session->setFlash('message', 'upload template success');
                return Yii::$app->response->redirect(['/template/index/']);
            }
        }
        return $this->render('index', [
            'model' => $formModel,
            'listFileTemplate' => $listFileTemplate
        ]);
    }
    
    /**
     * Displays detail page
     *
     * @return string
     */
    
    public function actionDetail($name){
        $request = Yii::$app->request;
        $file = FileHelper::findFiles(Yii::$app->params['folder_template'], ['only' => [$name . '*' . '.xlsx']]);
        if (count($file) == 0) {
            return Yii::$app->response->redirect(['/site/error']);
        }
        $formModel = new FormTemplate();
        $formModel->name_template = $name;
        $contentFileConfig = file_get_contents(Yii::$app->params['folder_template'] . $name . ".php");
        if ($request->isPost) {
            $dataPost = $request->Post();
            if (array_key_exists('download', $dataPost)) {
                $formModel->downloadTempate();
            }
            if (array_key_exists('edit', $dataPost)) {
                $formModel->name_template = $name;
                $formModel->template = UploadedFile::getInstance($formModel, 'template');
                $formModel->note_template = UploadedFile::getInstance($formModel, 'note_template');
                if ($formModel->validate()) {
                    $formModel->upload();
                    Yii::$app->session->setFlash('message', 'Edit template success');
                    return Yii::$app->response->redirect(['/template/detail/', 'name' => $name]);
                }
            }
        }
        return $this->render('detail', [
            'model' => $formModel,
            'contentFileConfig' => $contentFileConfig
        ]);
    }
}
