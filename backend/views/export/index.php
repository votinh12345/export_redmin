<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\Session;

$this->title = 'List Project';
?>
<!--breadcrumbs-->
<div id="content-header">
    <div id="breadcrumb">
      <a href="<?= Url::to(['/']); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href="#" class="tip-bottom">List Project</a>
    </div>
    <h1>List Project</h1>
</div>
<!--End-breadcrumbs-->
<div class="container-fluid">
    <hr>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>List Project</h5>
                </div>
                
                <div class="widget-content nopadding">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper" role="grid">
                        <?php ActiveForm::begin(['options' => ['id' => 'form']]); ?>
                        <?php if ($dataProvider->getTotalCount() == 0) : ?>
                            <p class="txtWarning"><span class="iconNo">Data does not exist</span></p>
                        <?php else : ?>

                            <?php Pjax::begin(); ?>
                            <?=
                                GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'layout' => '{items}<div class="fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">'
                                    . '<div id="paging" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers">{pager}</div></div>',
                                    'summary' => '<div class="pageList_data"><strong>ALL {totalCount} Item {begin} ～ {end}</strong>'
                                    . '</div><div class="pageList_del"><div class="pageList_del_item"></div></div>',
                                    'rowOptions'   => function ($model, $index, $widget, $grid) {
                                        if ($index % 2 == 0) {
                                                return [
                                                    'id' => $model['id'],
                                                    'class' => 'odd',
                                                    'onclick' => 'location.href="'
                                                        . Yii::$app->urlManager->createUrl('exam/detail') 
                                                        . '/"+(this.id);'
                                                ];
                                        } else {
                                            return [
                                                'id' => $model['id'],
                                                'class' => 'even',
                                                'onclick' => 'location.href="'
                                                    . Yii::$app->urlManager->createUrl('exam/detail') 
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
                                                return '<a data-pjax="0" href="' . Url::to(['/exam/detail',
                                                            'examId' => $data["id"]]) . '">' . $data['id'] . '</a>';
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
                                    'tableOptions' => ['class' => 'table table-bordered table-striped'],
                                    'pager' => [
                                        'prevPageLabel' => 'Prev',
                                        'nextPageLabel' => 'Next',
                                        'activePageCssClass' => 'paginate_button active',
                                        'disabledPageCssClass' => 'paginate_button previous disabled',
                                        'options' => [
                                            'class' => 'pagination_custom',
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
    </div>
</div>