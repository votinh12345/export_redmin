<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\FileHelper;
use yii\base\Model;
/**
 * Login form
 */
class FormTemplate extends Model
{
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
     * upload file
     * 
     * Auth : HienNV6244
     * Created : 17-07-2017
     */
    public function upload()
    {
        $fileTemplateName = $this->type_template . '_' . $this->name_template. '.' . $this->template->extension;
        $this->template->saveAs('export_file/' . $fileTemplateName);
        $fileTemplateNote = $this->type_template . '_' . $this->name_template. '.' . $this->note_template->extension;
        $this->note_template->saveAs('export_file/' . $fileTemplateNote);
        return true;
    }
    
    /*
     * List file template
     * 
     * Auth: HienNV6244
     * Created : 17-07-2017
     */
    public function getListImageFiles(){
        $fileName = $this->type_template;
        $list = [];
        $listFile = FileHelper::findFiles(Yii::$app->params['folder_template'], ['only' => [$fileName . '_' . '*' . '.xlsx']]);
        if (count($listFile) > 0) {
            foreach ($listFile as $key => $value) {
                $list[] =  str_replace(Yii::$app->params['folder_template'], "", $value);
            }
        }
        return $list;
    }
}