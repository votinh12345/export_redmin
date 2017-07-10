<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'css/bootstrap.min.css',
        'css/bootstrap-responsive.min.css',
        'css/uniform.css',
        'css/fullcalendar.css',
        'css/matrix-style.css',
        'css/matrix-media.css',
        'css/select2.css',
        'font-awesome/css/font-awesome.css',
        'css/jquery.gritter.css',
        'css/custom.css'
    ];
    public $js = [
        'js/jquery.min.js',
        'js/jquery.ui.custom.js',
        'js/bootstrap.min.js',
        'js/bootstrap-colorpicker.js',
        'js/bootstrap-datepicker.js',
        'js/jquery.toggle.buttons.js',
        'js/masked.js',
        'js/jquery.uniform.js',
        'js/select2.min.js',
        'js/matrix.js',
        'js/matrix.form_common.js',
        'js/redmine.js',
//        'js/wysihtml5-0.3.0.js',
//        'js/jquery.peity.min.js',
//        'js/bootstrap-wysihtml5.js'
//        'js/jquery.flot.min.js',
//        'js/jquery.flot.resize.min.js',
//        'js/jquery.peity.min.js',
//        'js/fullcalendar.min.js',
//        'js/matrix.js',
//        'js/matrix.dashboard.js',
//        'js/jquery.gritter.min.js',
//        'js/matrix.interface.js',
//        'js/matrix.chat.js',
//        'js/jquery.validate.js',
//        'js/matrix.form_validation.js',
//        'js/jquery.wizard.js',
//        'js/jquery.uniform.js',
//        'js/select2.min.js',
//        'js/matrix.popover.js',
//        'js/jquery.dataTables.min.js',
//        'js/matrix.tables.js',
//        'js/matrix.form_common.js'
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
