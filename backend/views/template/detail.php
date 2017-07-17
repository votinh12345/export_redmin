<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\FormTemplate;

$this->title = 'Template';
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
    <?php if (count($listFileTemplate) > 0) :?>
    <div class="row">
        <div class="box-header with-border">
            <h3 class="box-title">List Template</h3>
        </div>
        <!-- /.box-header -->
        <?php foreach ($listFileTemplate as $key => $value) :?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
                <h3>&nbsp;</h3>
                <p>&nbsp;</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <p href="#" class="small-box-footer"><?= htmlspecialchars($value)?></p>
          </div>
        </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Template Form Elements</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?php $form = ActiveForm::begin(['errorCssClass' => 'error']); ?>
                    
                    <div class="box-body">
                        <div class="form-group">
                            <label for="type_template">Type Template</label>
                            <?= $form->field($model, 'type_template')->dropDownList(FormTemplate::$TYPE_TEMPLATE, ['class' => 'form-control'])->label(false);?>
                            
                        </div>
                        <div class="form-group">
                            <label for="name_template">Name Template</label>
                            <?= $form->field($model, 'name_template', ['options' => ['class' => ''], 'template' => '{input}{error}'])->textInput(['placeholder' => 'Name Template', 'class' => 'form-control'])->label(false); ?>
                            
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
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'title'=>'Submit']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>