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
        
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <!--Header-part-->
        <div id="header">
            <h1><a href="<?= Url::to(['/']); ?>">Matrix Admin</a></h1>
        </div>
        <!--close-Header-part-->
        <!--top-Header-menu-->
        <div id="user-nav" class="navbar navbar-inverse">
            <ul class="nav">
                <li  class="dropdown" id="profile-messages" ><a title="" href="javascript:void(0)" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome Admin</span><b class="caret"></b></a></li>
                <li class=""><a href="javascript:void(0)"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
            </ul>
        </div>
        <!--close-top-Header-menu-->

        <!--sidebar-menu-->
        <div id="sidebar"><a href="<?= Url::to(['/']); ?>" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
            <ul>
                <li class="<?= (Yii::$app->controller->id == 'site') ? 'active' : '';?>"><a href="<?= Url::to(['/']); ?>"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
                <li class="<?= (Yii::$app->controller->id == 'report') ? 'active' : '';?>"> <a href="charts.html"><i class="icon icon-signal"></i> <span>Report</span></a> </li>
                <li> <a href="widgets.html"><i class="icon icon-inbox"></i> <span>Export Excel With CSV</span></a> </li>
            </ul>
        </div>
        <!--sidebar-menu-->
        
        <!--main-container-part-->
        <div id="content">
          <!--Content-->
          <?= $content ?>
          <!--End-content-->
        </div>
        <!--end-main-container-part-->
        
        <!--Footer-part-->

        <div class="row-fluid">
          <div id="footer" class="span12"> 2017 &copy; Matrix Admin. Brought to you by <a href="http://themedesigner.in">Themedesigner.in</a> </div>
        </div>

        <!--end-Footer-part-->
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
