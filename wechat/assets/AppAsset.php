<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace wechat\assets;

use yii\web\AssetBundle;
use yii\web\View;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css?2017',
        'css/auction.css?2017',
        'flatlab/css/bootstrap-reset.css',
        'css/Font-Awesome-3.2.1/css/font-awesome.min.css',
        'css/mui.min.css',
        'css/mui-reset.css'
    ];
    public $jsOptions=[
    'position'=> View::POS_HEAD
    ];
    public $js = [    
        'js/common.js',
        'js/jquery.downCount.js?2017',
        'js/mui.min.js'
      
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
