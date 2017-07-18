<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Model;
use yii\data\SqlDataProvider;
/**
 * Login form
 */
class FormExport extends Model
{
    public $sql_single_project;
    public $sql_multiple_project;
    public $sql;
    
    

    public function rules()
    {
        return [
            [['sql'], 'required'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sql_single_project' => 'Sql Single Project',
            'sql_multiple_project' => 'Sql Multiple Project',
            'sql' => 'SQL'
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
        return ['sql_single_project', 'sql_multiple_project', 'sql'];
        
    }
    
    /*
     * Get all data detail
     * 
     * Auth : HienNV6244
     * Created : 11-07-2017
     */
    public function getAllDataDetail($query = NULL) {
        if (empty($query)) {
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
        }
        
        $count = Yii::$app->db->createCommand($query)->queryScalar();
        
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
            //'totalCount' => $count,
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
}