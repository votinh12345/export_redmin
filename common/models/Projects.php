<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "projects".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $homepage
 * @property integer $is_public
 * @property integer $parent_id
 * @property string $created_on
 * @property string $updated_on
 * @property string $identifier
 * @property integer $status
 * @property integer $lft
 * @property integer $rgt
 * @property integer $inherit_members
 * @property integer $default_version_id
 */
class Projects extends \yii\db\ActiveRecord
{
    const IS_PUBLIC = 0;
    const IS_NOT_PUBLIC = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['is_public', 'parent_id', 'status', 'lft', 'rgt', 'inherit_members', 'default_version_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['name', 'homepage', 'identifier'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'homepage' => 'Homepage',
            'is_public' => 'Is Public',
            'parent_id' => 'Parent ID',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'identifier' => 'Identifier',
            'status' => 'Status',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'inherit_members' => 'Inherit Members',
            'default_version_id' => 'Default Version ID',
        ];
    }
    
    /**
     * get list project
     * @Date 10-07-2017 
     */
    public function getData() {
        $query = new \yii\db\Query();
        $query->select(['projects.*'])
                ->from('projects');
        $query->andFilterWhere(['=', 'is_public' , self::IS_PUBLIC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['pageSize']
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC
                ]
            ],
        ]);
        $dataProvider->sort->attributes['id'] = [
            'desc' => ['projects.id' => SORT_DESC],
            'asc' => ['projects.id' => SORT_ASC],
        ];
        return $dataProvider;
    }
}
