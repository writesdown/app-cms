<?php
use common\models\Option;

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = require(__DIR__ . '/../../tests/codeception/config/frontend/acceptance.php');

$application = new yii\web\Application($config);

/* Time Zone */
$application->timeZone = Option::get('time_zone');

/* Date Time */
$application->formatter->dateFormat = 'php:' . Option::get('date_format');
$application->formatter->timeFormat = 'php:' . Option::get('time_format');
$application->formatter->datetimeFormat = 'php:' . Option::get('date_format') . ' ' . Option::get('time_format');

/* Theme */
$application->view->theme->basePath = '@themes/' . Option::get('theme');
$application->view->theme->baseUrl = '@web/themes/' . Option::get('theme');
$application->view->theme->pathMap = [
    '@app/views' => '@themes/' . Option::get('theme'),
    '@app/views/post' => '@themes/' . Option::get('theme') . '/post'
];

/* Theme Config */
$themeConfigFile = Yii::getAlias('@themes/') . Option::get('theme') . '/config/main.php';

if(is_file($themeConfigFile)){
    $themeConfig = require($themeConfigFile);
    if(isset($themeConfig['frontend'])){
        $application->params = \yii\helpers\ArrayHelper::merge($application->params, $themeConfig['frontend']);
    }
}

$application->run();