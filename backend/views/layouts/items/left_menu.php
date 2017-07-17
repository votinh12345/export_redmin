<?php
use yii\helpers\Url;
use common\models\Projects;

$firtProject = Projects::find()->one();
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Admin</p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview">
                <a href="javascript:void(0)">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="<?= (Yii::$app->controller->id == 'site') ? 'active' : '';?>">
                <a href="<?= Url::to(['/site/index']); ?>">
                    <i class="fa fa-files-o"></i>
                    <span>List Project</span>
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/export/multiple_project']); ?>">
                    <i class="fa fa-th"></i> <span>Export Excel Multiple Project</span>
                </a>
            </li>
            <?php if (count($firtProject) > 0) :?>
            <li>
                <a href="<?= Url::to(['/export/single/'. $firtProject->id]); ?>">
                    <i class="fa fa-th"></i> <span>Export Excel Single Project</span>
                </a>
            </li>
            <?php endif;?>
            <li class="<?= (Yii::$app->controller->id == 'template') ? 'active' : '';?>">
                <a href="<?= Url::to(['/template/detail/single']); ?>">
                    <i class="fa fa-file-excel-o"></i>
                    <span>Template Export</span>
                </a>
            </li>
            
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>