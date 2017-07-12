<?php

use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\Session;
use backend\models\FormReport;

$this->title = 'Detail report';
$flagShowDate = $flagShowUser = $flagShowActivity = $flagShowComment = $flagShowHours = 0;
$flagShowSpentOn1 = $formModelReport->isShowSpentOn1();
$flagShowSpentOn2 = $formModelReport->isShowSpentOn2();
$flagShowSpentOn = $formModelReport->isShowSpentOn();
$flagShowHours1 = $formModelReport->isShowHours1();
$flagShowHours2 = $formModelReport->isShowHours2();
$flagShowButtonExportByMember = $formModelReport->isShowButtonExportByMember();
$flagShowButtonReportMonth = $formModelReport->isShowButtonExportReportMonth();
if ($formModelReport->spent_on == '*') {
    $flagShowDate = true;
}
if ($formModelReport->filter_cb_hours == '*') {
    $flagShowHours = true;
}
?>
<script>
  $(function () {
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Date picker
    $('#formreport-values_spent_on_1, #formreport-values_spent_on_2').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd',
    })
    //Initialize Select2 Elements
    $('.select2').select2()
  })
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Report project <?= $projectsItem->identifier ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['/']); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Report</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Form Filter</h3>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <?php
                    $form = ActiveForm::begin([
                                'action' => ['report/detail/' . $projectsItem->id],
                                'method' => 'get',
                                'options' => [
                                    'class' => 'form-horizontal custom-form'
                                ]
                    ]);
                ?>
                <div class="col-md-12 custom-form">
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($formModelReport, 'check_spent_on', ['template' => "{input}",])->checkbox([])->label('Date'); ?>
                        </div>
                        <div class="col-md-4" id="filter_date" style="<?= ($formModelReport->check_spent_on == 0) ? 'display:none;' : '' ?>">
                            <?= Html::activeDropDownList($formModelReport, 'spent_on', FormReport::$FILTER_DATE, ['class' => 'form-control']); ?>
                        </div>
                        <div class="col-md-6" id="values_filter_date">
                            <span id="values_spent_on_1" class="col-md-6" style="margin-right: 20px; <?= (!$flagShowSpentOn1) ? 'display:none;' : '' ?>">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                    </div>
                                    <?= Html::activeTextInput($formModelReport, 'values_spent_on_1', ['class' => 'form-control pull-right']); ?>
                                </div>
                            </span>
                            <span id="values_spent_on_2" class="col-md-6" style="margin-right: 20px; <?= (!$flagShowSpentOn2) ? 'display:none;' : '' ?>">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                      <i class="fa fa-calendar"></i>
                                    </div>
                                    <?= Html::activeTextInput($formModelReport, 'values_spent_on_2', ['class' => 'form-control pull-right']); ?>
                                </div>
                            </span>
                            <span id="values_spent_on" class="col-md-6" style="<?= (!$flagShowSpentOn) ? 'display:none;' : '' ?>">
                                <?= Html::activeTextInput($formModelReport, 'values_spent_on', ['class' => 'form-control']); ?>
                                <span class="add-on">days</span> 
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($formModelReport, 'cb_user_id', ['template' => "{input}",])->checkbox()->label('User'); ?>
                        </div>
                        <div class="col-md-4" id="filter_user_id" style="<?= (!$formModelReport->cb_user_id) ? 'display:none' : '' ?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_user_id', FormReport::$FILTER_USER, ['class' => 'form-control']); ?>
                        </div>
                        <div class="col-md-6" id="user_id" style="<?= (!$formModelReport->cb_user_id) ? 'display:none' : '' ?>">
                            <?= Html::activeDropDownList($formModelReport, 'user_id', $listUserByProject, ['multiple' => 'multiple', 'class' => 'form-control select2', 'style' => 'width: 100%;']); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($formModelReport, 'cb_activity_id', ['template' => "{input}",])->checkbox()->label('Activity'); ?>
                        </div>
                        <div class="col-md-4" id="filter_activity_id" style="<?= (!$formModelReport->cb_activity_id) ? 'display:none' : '' ?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_activity_id', FormReport::$FILTER_ACTIVITY, ['class' => 'form-control']); ?>
                        </div>
                        <div class="col-md-6" id="value_activity_id" style="<?= (!$formModelReport->cb_activity_id) ? 'display:none' : '' ?>">
                            <?= Html::activeDropDownList($formModelReport, 'activity_id', $listActivity, ['multiple' => 'multiple', 'class' => 'form-control select2', 'style' => 'width: 100%;']); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($formModelReport, 'cb_comments', ['template' => "{input}",])->checkbox()->label('Comment'); ?>
                        </div>
                        <div class="col-md-4" id="filter_cb_comments" style="<?= (!$formModelReport->cb_comments) ? 'display:none' : '' ?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_cb_comments', FormReport::$FILTER_COMMENT, ['class' => 'form-control']); ?>
                        </div>
                        <div class="col-md-6" id="value_comments" style="<?= (!$formModelReport->cb_comments) ? 'display:none' : '' ?>">
                            <?= Html::activeTextInput($formModelReport, 'comments', ['class' => 'form-control']); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($formModelReport, 'cb_hours', ['template' => "{input}",])->checkbox()->label('Hours'); ?>
                        </div>
                        <div class="col-md-4" id="filter_cb_hours" style="<?= (!$formModelReport->cb_hours) ? 'display:none' : '' ?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_cb_hours', FormReport::$FILTER_HOURS, ['class' => 'form-control']); ?>
                        </div>
                        <div class="col-md-6" id="values_hours" style="<?= (!$formModelReport->cb_hours) ? 'display:none' : '' ?>">
                            <span id="values_hours_1" class="col-md-5" style="margin-right: 20px; <?= (!$flagShowHours1) ? 'display:none;' : '' ?>">
                                <?= Html::activeTextInput($formModelReport, 'values_hours_1', ['class' => 'form-control']); ?>
                            </span>
                            <span id="values_hours_2" class="col-md-5" style="margin-right: 20px; <?= (!$flagShowHours2) ? 'display:none;' : '' ?>">
                                <?= Html::activeTextInput($formModelReport, 'values_hours_2', ['class' => 'form-control']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?= Html::submitButton('Apply', ['class' => 'btn btn-warning']) ?>
                        <a class="btn btn-default" href="<?php echo Url::to(['report/detail/' . $projectsItem->id]); ?>">Clear</a>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <?php ActiveForm::begin(['options' => ['id' => 'form']]); ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">Detail</a></li>
                            <li><a href="#tab_2" data-toggle="tab">Report</a></li>
                            <ul style="float:right" class="custom-report">
                                <?php if ($flagShowButtonExportByMember) : ?><li><input type="submit" class="btn bg-olive btn-flat margin" name="export-member" value=" Export Report Member"></li> <?php endif;?>
                                <?php if ($flagShowButtonReportMonth) : ?><li><input type="submit" class="btn bg-olive btn-flat margin" name="export-month" value="Export Report Month"></li><?php endif;?>
                            </ul>
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
                                                'attribute' => 'firstname',
                                                'label' => 'User',
                                                'content' => function ($data) {
                                                    return $data['firstname'] . ' ' . $data['lastname'];
                                                }
                                            ],
                                            [
                                                'attribute' => 'name_activity',
                                                'label' => 'Activity',
                                                'content' => function ($data) {
                                                    return $data['name_activity'];
                                                }
                                            ],
                                            [
                                                'attribute' => 'subject',
                                                'label' => 'Issue',
                                                'content' => function ($data) {
                                                    return $data['subject'];
                                                }
                                            ],
                                            [
                                                'attribute' => 'comments',
                                                'label' => 'Comment',
                                                'content' => function ($data) {
                                                    return $data['comments'];
                                                }
                                            ],
                                            [
                                                'attribute' => 'hours',
                                                'label' => 'Hours',
                                                'content' => function ($data) {
                                                    return $data['hours'];
                                                }
                                            ]
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
