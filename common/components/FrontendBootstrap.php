<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\components;

use common\models\Module;
use common\models\Option;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

/**
 * Class FrontendBootstrap
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class FrontendBootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $this->setTime($app);
        $this->setTheme($app);
        $this->setModule($app);
    }

    /**
     * Set time base on Option.
     *
     * @param Application $app the application currently running
     */
    protected function setTime($app)
    {
        /* TIME ZONE */
        $app->timeZone = Option::get('time_zone');

        /* DATE TIME */
        $app->formatter->dateFormat = 'php:' . Option::get('date_format');
        $app->formatter->timeFormat = 'php:' . Option::get('time_format');
        $app->formatter->datetimeFormat = 'php:' . Option::get('date_format') . ' ' . Option::get('time_format');
    }

    /**
     * Set theme params
     *
     * @param Application $app the application currently running
     */
    protected function setTheme($app)
    {
        $app->view->theme->basePath = '@themes/' . Option::get('theme');
        $app->view->theme->baseUrl = '@web/themes/' . Option::get('theme');
        $app->view->theme->pathMap = [
            '@app/views'      => '@themes/' . Option::get('theme'),
            '@app/views/post' => '@themes/' . Option::get('theme') . '/post',
        ];
        $themeParamPath = Yii::getAlias('@themes/') . Option::get('theme') . '/config/params.php';

        if (is_file($themeParamPath)) {
            $themeParam = require($themeParamPath);
            if (isset($themeParam['frontend'])) {
                $app->params = ArrayHelper::merge($app->params, $themeParam['frontend']);
            }
        }
    }

    /**
     * Set modules.
     *
     * @param Application $app the application currently running
     */
    protected function setModule($app)
    {
        foreach (Module::getActiveModules() as $module) {
            // Get module backend config.
            if ($moduleConfig = $module->frontendConfig) {
                // Set module.
                $app->setModules([$module->module_name => $moduleConfig]);
                // Merge application params with exist module params.
                if (is_file($module->paramPath)) {
                    $moduleParam = require($module->paramPath);
                    if (isset($moduleParam['frontend'])) {
                        $app->params = ArrayHelper::merge($app->params, $moduleParam['frontend']);
                    }
                }
                // Bootstrap injection.
                if ($module->module_fb) {
                    $component = $app->getModule($module->module_name);
                    if ($component instanceof BootstrapInterface) {
                        $component->bootstrap($app);
                    }
                }
            }
        }
    }
}
