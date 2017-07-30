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
        $file = $modelReport->getAllDataReport();
        //senmail
        if ($file) {
            $message = Yii::$app->mailer->compose(['text' => 'mail_report'], [])
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo(Yii::$app->params['mailTo'])
                    ->setSubject(Yii::$app->params['subject']);
            $message->attach($file);
            $message->send();
            return true;
        }
        return false;
    }

}
