<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use Exception;
use console\models\Report;

/**
 * Image Statistic controller
 */
class ReportController extends \yii\console\Controller {
    
    public function actionReport() {
        $modelReport = new Report();
        $modelReport->getAllDataReport();
    }
}