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
 * Class BackendBootstrap
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class BackendBootstrap implements BootstrapInterface
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
        /* THEME CONFIG */
        $themeParamPath = Yii::getAlias('@themes/') . Option::get('theme') . '/config/params.php';

        if (is_file($themeParamPath)) {
            $themeParam = require($themeParamPath);
            if (isset($themeParam['backend'])) {
                $app->params = ArrayHelper::merge($app->params, $themeParam['backend']);
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
            if ($moduleConfig = $module->backendConfig) {
                // Set module.
                $app->setModules([$module->module_name => $moduleConfig]);
                // Merge application params with exist module params.
                if (is_file($module->paramPath)) {
                    $moduleParam = require($module->paramPath);
                    if (isset($moduleParam['backend'])) {
                        $app->params = ArrayHelper::merge($app->params, $moduleParam['backend']);
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
