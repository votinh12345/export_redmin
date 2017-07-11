<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\Session;
$this->title = 'List Project';
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        List Project
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['/']); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">List Project</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List Project</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php ActiveForm::begin(['options' => ['id' => 'form']]); ?>
                        <?php if ($dataProvider->getTotalCount() == 0) : ?>
                            <p class="txtWarning"><span class="iconNo">Data does not exist</span></p>
                        <?php else : ?>

                            <?php Pjax::begin(); ?>
                            <?=
                                GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'layout' => '<div class="dataTables_wrapper form-inline dt-bootstrap">{items}<div class="col-sm-5">{summary}</div>'
                                    . '<div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
                                    'summary' => '<div class="dataTables_info">ALL {totalCount} Item {begin} ～ {end}</div>',
                                    'rowOptions'   => function ($model, $index, $widget, $grid) {
                                        if ($index % 2 == 0) {
                                                return [
                                                    'id' => $model['id'],
                                                    'class' => 'odd',
                                                    'onclick' => 'location.href="'
                                                        . Yii::$app->urlManager->createUrl('report/detail') 
                                                        . '/"+(this.id);'
                                                ];
                                        } else {
                                            return [
                                                'id' => $model['id'],
                                                'class' => 'even',
                                                'onclick' => 'location.href="'
                                                    . Yii::$app->urlManager->createUrl('report/detail') 
                                                    . '/"+(this.id);'
                                            ];
                                        }

                                    },
                                    'columns' => [
                                        [
                                            'attribute' => 'id',
                                            'label' => 'Project ID',
                                            'headerOptions' => ['class' => ''],
                                            'contentOptions' => ['class' => 'sorting_1'],
                                            'content' => function ($data) {
                                                return $data['id'];
                                            }
                                        ],
                                        [
                                            'attribute' => 'name',
                                            'label' => 'Name',
                                            'headerOptions' => ['class' => ''],
                                            'content' => function ($data) {
                                                return $data['name'];
                                            }
                                        ],
                                        [
                                            'attribute' => 'description',
                                            'label' => 'Description',
                                            'headerOptions' => ['class' => '', 'width' => '40%'],
                                            'contentOptions' => ['width' => '40%'],
                                            'content' => function ($data) {
                                                return $data['description'];
                                            }
                                        ],
                                        [
                                            'attribute' => 'created_on',
                                            'label' => 'Created On',
                                            'content' => function ($data) {
                                                return $data['created_on'];
                                            }
                                        ],
                                        [
                                            'attribute' => 'updated_on',
                                            'label' => 'Updated On',
                                            'content' => function ($data) {
                                                return $data['updated_on'];
                                            }
                                        ],
                                    ],
                                    'tableOptions' => ['class' => 'table table-bordered table-hover'],
                                    'pager' => [
                                        'prevPageLabel' => 'Prev',
                                        'nextPageLabel' => 'Next',
                                        'activePageCssClass' => 'paginate_button active',
                                        'disabledPageCssClass' => 'paginate_button previous disabled',
                                        'options' => [
                                            'class' => 'pagination',
                                            'id' => 'pager-container',
                                        ],
                                    ],
                                ]);
                                ?>
                            <?php Pjax::end(); ?>
                        <?php endif; ?>
                    <?php ActiveForm::end(); ?>
                </div>
                
            </div>
        </div>
    </div>
</section>


