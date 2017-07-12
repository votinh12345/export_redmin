<?php

namespace common\components;

use Yii;
use yii\helpers\Url;
use yii\base\Component;
use yii\helpers\FileHelper;

class Utility extends Component
{
    public static function getDate($spentOn) {
        $dateNow = date('Y-m-d');
        $dayNow = date("D", strtotime($dateNow));
        switch ($spentOn) {
            case 'w':
                $firstDate = date('Y-m-d', strtotime('-' . Yii::$app->params['week']['minus'][$dayNow] . ' day'));
                $lastDate = date('Y-m-d', strtotime('+' . Yii::$app->params['week']['plus'][$dayNow] . ' day'));
                break;
            case 'lw':
                $date = date('Y-m-d', strtotime('-7 day'));
                $day = date("D", strtotime($date));
                $firstDate = date('Y-m-d', strtotime('-' . Yii::$app->params['week']['minus'][$day] . ' day'));
                $lastDate = date('Y-m-d', strtotime('+' . Yii::$app->params['week']['plus'][$day] . ' day'));
                break;
            case 'l2w':
                $date = date('Y-m-d', strtotime('-14 day'));
                $day = date("D", strtotime($date));
                $firstDate = date('Y-m-d', strtotime('-' . Yii::$app->params['week']['minus'][$day] . ' day'));
                $lastDate = date('Y-m-d', strtotime('+' . Yii::$app->params['week']['plus'][$day] . ' day'));
                break;
            case 'm':
                $firstDate = date('Y-m-01', strtotime($dateNow));
                $lastDate = date("Y-m-t", strtotime($dateNow));
                break;
            case 'lm':
                $date = date('Y-m-d', strtotime('-1 month'));
                $firstDate = date('Y-m-01', strtotime($date));
                $lastDate = date("Y-m-t", strtotime($date));
                break;
            default :
                break;
        }
        $result = [
            'firstDate' => $firstDate,
            'lastDate' => $lastDate
        ];
        
        return $result;
    }
    
    /*
     * Created folder
     * 
     * Auth : HienNV
     * Created : 12-07-2017
     */
    public static function createdFolder($folderName) {
        $dirParent = Url::to(Yii::$app->params['folderReport']). $folderName;
        if (!is_dir($dirParent)) {
            mkdir($dirParent, 0777);
        }
        return true;
    }
}
