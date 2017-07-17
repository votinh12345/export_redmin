<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?= Html::csrfMetaTags() ?>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <script src="<?= Url::base(); ?>/bower_components/jquery/dist/jquery.min.js"></script>
        <link rel="stylesheet" href="<?= Yii::$app->homeUrl; ?>/css/font-google.css">
        <script>
            var baseUrl = '<?= Url::base();;?>';
        </script>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini" >
        <div class="wrapper">
            <?php $this->beginBody(); ?>
            <!--Header-part-->
            <?= $this->render('items/header.php');?>
            <!--close-Header-part-->
            
            <!-- Left side column. contains the logo and sidebar -->
            <?= $this->render('items/left_menu.php');?>
            <!-- End Left side column. contains the logo and sidebar -->
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!--Content-->
                <?= $content ?>
                <!--End-content-->
            </div>
            
            <!-- /.content-wrapper -->
            <?= $this->render('items/footer.php');?>
            <!--end-Footer-part-->
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
