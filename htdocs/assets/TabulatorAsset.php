<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class TabulatorAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $baseUrl = '@web/';
    public $js = [
        'https://unpkg.com/tabulator-tables@4.9.3/dist/js/tabulator.min.js',
    ];
    public $css = [
        'https://unpkg.com/tabulator-tables@4.9.3/dist/css/bootstrap/tabulator_bootstrap4.min.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
