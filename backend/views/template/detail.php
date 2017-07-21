<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\FormTemplate;

$this->title = 'Detail Template';
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Template
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Template Elements</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- complete message -->
            <?php if (Yii::$app->session->hasFlash('message')) : ?>
            <div class="callout callout-info">
                <p><?= Yii::$app->session->getFlash('message') ?></p>
            </div>
            <?php endif;?>
            <!-- form start -->
            <div class="box box-primary">
                <?php $form = ActiveForm::begin(['errorCssClass' => 'error']); ?>
                <div class="box-header with-border">
                    <h3 class="box-title">Template Form Edit Elements</h3>
                    <button type="submit" class="btn btn-success" style="float: right" name="download">Download Template</button>
                </div>
                <?php ActiveForm::end(); ?>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                    <label>Content Config</label>
                    <textarea class="form-control" rows="10" placeholder="Enter ..." disabled><?= print_r($contentFileConfig);?></textarea>
                    </div>
                </div>
                <?php $form = ActiveForm::begin(['errorCssClass' => 'error']); ?>
                <div class="box-body">
                    <div class="form-group">
                        <label for="name_template">Name Template</label>
                        <?= $form->field($model, 'name_template', ['options' => ['class' => ''], 'template' => '{input}{error}'])->textInput(['placeholder' => 'Name Template', 'class' => 'form-control', 'disabled' => 'disabled'])->label(false); ?>

                    </div>
                    <div class="form-group">
                        <label for="template">Template</label>
                        <?= $form->field($model, 'template', ['options' => ['class' => ''], 'template' => '{input}{error}'])->fileInput()->label(false); ?>
                    </div>
                    <div class="form-group">
                        <label for="note_template">File input</label>
                        <?= $form->field($model, 'note_template', ['options' => ['class' => ''], 'template' => '{input}{error}'])->fileInput()->label(false); ?>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'title'=>'Submit', 'name'=>'edit']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            
        </div>
    </div>
</section>