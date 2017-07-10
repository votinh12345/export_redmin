<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\FormReport;

$this->title = 'Detail report';
$flagShowDate = $flagShowUser = $flagShowActivity = $flagShowComment = $flagShowHours = 0;
$flagShowSpentOn1 = $formModelReport->isShowSpentOn1();
$flagShowSpentOn2 = $formModelReport->isShowSpentOn2();
$flagShowSpentOn = $formModelReport->isShowSpentOn();
$flagShowHours1 = $formModelReport->isShowHours1();
$flagShowHours2 = $formModelReport->isShowHours2();
if ($formModelReport->spent_on == '*') {
    $flagShowDate = true;
}
if ($formModelReport->filter_cb_hours == '*') {
    $flagShowHours = true;
}
?>

<!--breadcrumbs-->
<div id="content-header">
    <div id="breadcrumb">
      <a href="<?= Url::to(['/']); ?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
      <a href="javascript:void(0)" class="tip-bottom">Report</a>
    </div>
    <h1>Report project <?= $projectsItem->identifier?></h1>
</div>
<!--End-breadcrumbs-->

<div class="container-fluid">
    <hr>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    <h5>Form Filter</h5>
                </div>
                <div class="widget-content nopadding">
                    <?php
                    $form = ActiveForm::begin([
                                'action' => ['report/detail/'. $projectsItem->id],
                                'method' => 'get',
                                'options' => [
                                    'class' => 'form-horizontal custom-form'
                                 ]
                    ]);
                    ?>
                    <div class="control-group">
                        <div class="controls span2">
                            <?= $form->field($formModelReport, 'check_spent_on', ['template' => "{input}",])->checkbox()->label('Date'); ?>
                        </div>
                        <div class="controls span4" id="filter_date" style="<?= ($formModelReport->check_spent_on == 0) ? 'display:none;' : ''?>">
                            <?= Html::activeDropDownList($formModelReport, 'spent_on', FormReport::$FILTER_DATE, []); ?>
                        </div>
                        <div class="controls span6" id="values_filter_date">
                            <span id="values_spent_on_1" style="margin-right: 20px; <?= (!$flagShowSpentOn1) ? 'display:none;' : ''?>">
                                <div  data-date="<?= date('m-d-Y')?>" class="input-append date datepicker">
                                    <?= Html::activeTextInput($formModelReport, 'values_spent_on_1', ['class' => 'span11', 'data-date-format' => 'mm-dd-yyyy']); ?>
                                <span class="add-on"><i class="icon-th"></i></span> </div>
                            </span>
                            <span id="values_spent_on_2" style="margin-right: 20px; <?= (!$flagShowSpentOn2) ? 'display:none;' : ''?>">
                                <div  data-date="<?= date('m-d-Y')?>" class="input-append date datepicker">
                                    <?= Html::activeTextInput($formModelReport, 'values_spent_on_2', ['class' => 'span11', 'data-date-format' => 'mm-dd-yyyy']); ?>
                                <span class="add-on"><i class="icon-th"></i></span> </div>
                            </span>
                            <span id="values_spent_on" style="<?= (!$flagShowSpentOn) ? 'display:none;' : ''?>">
                                <?= Html::activeTextInput($formModelReport, 'values_spent_on', ['class' => 'span6']); ?>
                                <span class="add-on">days</span> 
                            </span>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="controls span2">
                            <?= $form->field($formModelReport, 'cb_user_id', ['template' => "{input}",])->checkbox()->label('User'); ?>
                        </div>
                        <div class="controls span4" id="filter_user_id" style="<?= (!$formModelReport->cb_user_id) ? 'display:none' : ''?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_user_id', FormReport::$FILTER_USER, []); ?>
                        </div>
                        <div class="controls span6" id="user_id" style="<?= (!$formModelReport->cb_user_id) ? 'display:none' : ''?>">
                            <?= Html::activeDropDownList($formModelReport, 'user_id', $listUserByProject, ['multiple' => 'multiple', 'class' => 'span10']); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="controls span2">
                            <?= $form->field($formModelReport, 'cb_activity_id', ['template' => "{input}",])->checkbox()->label('Activity'); ?>
                        </div>
                        <div class="controls span4" id="filter_activity_id" style="<?= (!$formModelReport->cb_activity_id) ? 'display:none' : ''?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_activity_id', FormReport::$FILTER_ACTIVITY, []); ?>
                        </div>
                        <div class="controls span6" id="value_activity_id" style="<?= (!$formModelReport->cb_activity_id) ? 'display:none' : ''?>">
                            <?= Html::activeDropDownList($formModelReport, 'activity_id', $listActivity, ['multiple' => 'multiple', 'class' => 'span10']); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="controls span2">
                            <?= $form->field($formModelReport, 'cb_comments', ['template' => "{input}",])->checkbox()->label('Comment'); ?>
                        </div>
                        <div class="controls span4" id="filter_cb_comments" style="<?= (!$formModelReport->cb_comments) ? 'display:none' : ''?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_cb_comments', FormReport::$FILTER_COMMENT, []); ?>
                        </div>
                        <div class="controls span6" id="value_comments" style="<?= (!$formModelReport->cb_comments) ? 'display:none' : ''?>">
                            <?= Html::activeTextInput($formModelReport, 'comments', ['class' => 'span6']); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="controls span2">
                            <?= $form->field($formModelReport, 'cb_hours', ['template' => "{input}",])->checkbox()->label('Hours'); ?>
                        </div>
                        <div class="controls span4" id="filter_cb_hours" style="<?= (!$formModelReport->cb_hours) ? 'display:none' : ''?>">
                            <?= Html::activeDropDownList($formModelReport, 'filter_cb_hours', FormReport::$FILTER_HOURS, []); ?>
                        </div>
                        <div class="controls span6" id="values_hours" style="<?= (!$formModelReport->cb_hours) ? 'display:none' : ''?>">
                            <span id="values_hours_1" style="margin-right: 20px; <?= (!$flagShowHours1) ? 'display:none;' : ''?>">
                                <?= Html::activeTextInput($formModelReport, 'values_hours_1', ['class' => 'span4']); ?>
                            </span>
                            <span id="values_hours_2" style="margin-right: 20px; <?= (!$flagShowHours2) ? 'display:none;' : ''?>">
                                <?= Html::activeTextInput($formModelReport, 'values_hours_2', ['class' => 'span4']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <?= Html::submitButton('Apply', ['class' => 'btn btn-warning']) ?>
                        <a class="btn btn-default" href="<?php echo Url::to(['report/detail/'. $projectsItem->id]); ?>">Clear</a>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>