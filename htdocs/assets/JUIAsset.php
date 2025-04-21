<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class JUIAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $baseUrl = '@web/';
    public $js = [
        'js/jquery-ui-1.12.1.custom/jquery-ui.min.js',
    ];
    public $css = [
        'js/jquery-ui-1.12.1.custom/jquery-ui.min.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
