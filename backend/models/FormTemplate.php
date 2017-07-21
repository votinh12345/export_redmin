<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\FileHelper;
use yii\base\Model;
use ZipArchive;
/**
 * Login form
 */
class FormTemplate extends Model
{
    const SCENARIO_ADD = 'add';
    
    public static $TYPE_TEMPLATE = [
        'single' => 'Single Template',
        'multiple' => 'Multiple Template'
    ];
    
    public $type_template;
    public $name_template;
    public $template;
    public $note_template;
    
    public function rules()
    {
        return [
            [['name_template', 'template', 'note_template'], 'required'],
            [['template'], 'file', 'extensions' => 'xlsx', 'maxSize'=> 1*1024*1024,
                'tooBig' => \Yii::t('app', 'file size upload'), 'checkExtensionByMimeType' => false ,
                'wrongExtension' => \Yii::t('app', 'Extension xlsx')],
            [['note_template'], 'file', 'extensions' => 'php', 'maxSize'=> 1*1024*1024,
                'tooBig' => \Yii::t('app', 'file size upload'), 'checkExtensionByMimeType' => false ,
                'wrongExtension' => \Yii::t('app', 'Extension xlsx')],
            ['name_template', 'validateNameTemplate', 'on' => self::SCENARIO_ADD],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name_template' => 'Name Template',
            'template' => 'Template',
            'note_template' => 'Note Template',
            'type_template' => 'Type Template'
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
        return ['name_template', 'template', 'note_template', 'type_template'];
        
    }
    
    /*
     * validate name template
     * 
     * Auth :
     * Created : 21-07-2017
     */
    
    public function validateNameTemplate($attribute) {
        $listFile = FileHelper::findFiles(Yii::$app->params['folder_template'], ['only' => [$this->$attribute . '*' . '.xlsx']]);
        if (count($listFile) > 0) {
            $this->addError($attribute, \Yii::t('app', 'Template already exists!'));
        }
    }
    /*
     * upload file
     * 
     * Auth : HienNV6244
     * Created : 17-07-2017
     */
    public function upload()
    {
        $fileTemplateName = $this->name_template. '.' . $this->template->extension;
        $this->template->saveAs('export_file/' . $fileTemplateName);
        $fileTemplateNote = $this->name_template. '.' . $this->note_template->extension;
        $this->note_template->saveAs('export_file/' . $fileTemplateNote);
        return true;
    }
    
    /*
     * List file template
     * 
     * Auth: HienNV6244
     * Created : 17-07-2017
     */
    public function getListImageFiles($flag = true){
        $list = [];
        $listFile = FileHelper::findFiles(Yii::$app->params['folder_template'], ['only' => ['*' . '.xlsx']]);
        if (count($listFile) > 0) {
            foreach ($listFile as $key => $value) {
                $name = substr(str_replace([Yii::$app->params['folder_template'], '.xlsx'], "", $value), 1);
                if (!$flag) {
                    $list[$name] = $name;
                } else {
                    $list[] = $name;
                }
            }
        }
        return $list;
    }
    
    /*
     * Download template
     * 
     * Auth : Hiennv6244
     * Created : 21-07-2017
     */
    public function downloadTempate() {
        $compress = new ZipArchive();
        $compressFolder = Yii::$app->params['folder_template'] . $this->name_template. '.zip';
        $compress->open($compressFolder, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $fileNameExcel = Yii::$app->params['folder_template'] . $this->name_template . '.xlsx';
        $fileNamePhp = Yii::$app->params['folder_template'] . $this->name_template . '.php';
        if (file_exists($fileNameExcel)) {
            $compress->addFile($fileNameExcel);
        }
        if (file_exists($fileNameExcel)) {
            $compress->addFile($fileNamePhp);
        }
        //download file
        header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=$compressFolder"); 
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile("$compressFolder");
        return 0;
    }
}