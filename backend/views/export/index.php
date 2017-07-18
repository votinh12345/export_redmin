<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\Session;
use dosamigos\ckeditor\CKEditor;

$this->title = 'Export Excell';
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Export Excell
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['/']); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Export</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Form Export</h3>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <?php
                    $form = ActiveForm::begin([
                                'action' => ['export/index/'],
                                'method' => 'get',
                                'options' => [
                                    'class' => 'form-horizontal custom-form'
                                ]
                    ]);
                ?>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <?= $form->field($modelFormExprort, 'sql_single_project')->widget(CKEditor::className(), [
                            'options' => ['rows' => 10, 'readOnly' => true],
                            'preset' => 'basic',
                            'clientOptions' => ['height' => 300]

                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($modelFormExprort, 'sql_multiple_project')->widget(CKEditor::className(), [
                            'options' => ['rows' => 6,'readOnly' => true],
                            'preset' => 'basic',
                            'clientOptions' => ['height' => 300]
                        ]) ?>
                    </div>
                    
                    <div class="col-md-12">
                        <?= $form->field($modelFormExprort, 'sql')->widget(CKEditor::className(), [
                            'options' => ['rows' => 6],
                            'preset' => 'basic',
                            'clientOptions' => ['height' => 300]
                        ]) ?>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select</label>
                            <?= Html::activeDropDownList($modelFormExprort, 'template', $listFileTemplate, ['class' => 'form-control', 'style' => 'width: 100%;']); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?= Html::submitButton('Apply',[ 'name'=>'view', 'value' => 'view', 'class' => 'btn btn-primary btn-lg','data-pjax' => 0, 'id' => 'view']) ?>
                        <?= Html::submitButton('Export',[ 'name'=>'export', 'value' => 'export', 'class' => 'btn btn-success btn-lg','data-pjax' => 0, 'id' => 'export']) ?>
                        <a class="btn btn-warning btn-lg" href="<?php echo Url::to(['export/index']); ?>">Clear</a>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <hr>
            
            <div class="row">
                <!-- complete message -->
                <?php if (Yii::$app->session->hasFlash('message_export')) : ?>
                <div class="box-body"><div class="callout callout-danger"><h5><?= Yii::$app->session->getFlash('message_export') ?></h5></div></div>
                <?php endif; ?>
                <!-- /complete message -->
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <?php ActiveForm::begin(['options' => ['id' => 'form']]); ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Detail</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Report</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <?php if ($dataProvider->getTotalCount() == 0) : ?>
                                <p class="txtWarning"><span class="iconNo">Data does not exist</span></p>
                                <?php else : ?>
                                    <?php Pjax::begin(); ?>
                                    <?=
                                    GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'layout' => '<div class="mBoxitem_listinfo">{summary}</div>{items}<div class="mBoxitem_listinfo">'
                                        . '<div id="paging" class="light-theme simple-pagination">{pager}</div></div>',
                                        'summary' => '<div class="pageList_data"><strong>ALL {totalCount} Item {begin} ～ {end}</strong>'
                                        . '</div><div class="pageList_del"><div class="pageList_del_item"></div></div>',
                                        'columns' => [
                                            [
                                                'attribute' => 'spent_on',
                                                'label' => 'Date',
                                                'content' => function ($data) {
                                                    return $data['spent_on'];
                                                }
                                            ],
                                            [
                                                'attribute' => 'full_name',
                                                'label' => 'User',
                                                'content' => function ($data) {
                                                    if (isset($data['full_name'])) {
                                                        return $data['full_name'];
                                                    }
                                                    return '';
                                                }
                                            ],
//                                            [
//                                                'attribute' => 'name_activity',
//                                                'label' => 'Activity',
//                                                'content' => function ($data) {
//                                                    return $data['name_activity'];
//                                                }
//                                            ],
//                                            [
//                                                'attribute' => 'subject',
//                                                'label' => 'Issue',
//                                                'content' => function ($data) {
//                                                    return $data['subject'];
//                                                }
//                                            ],
//                                            [
//                                                'attribute' => 'comments',
//                                                'label' => 'Comment',
//                                                'content' => function ($data) {
//                                                    return $data['comments'];
//                                                }
//                                            ],
//                                            [
//                                                'attribute' => 'hours',
//                                                'label' => 'Hours',
//                                                'content' => function ($data) {
//                                                    return $data['hours'];
//                                                }
//                                            ]
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
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                            a1
                            </div>
                          <!-- /.tab-pane -->
                        </div>
                      <!-- /.tab-content -->
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        
    </div>
</section>