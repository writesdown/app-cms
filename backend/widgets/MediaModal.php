<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license
 */

namespace backend\widgets;

use backend\assets\MediaModalAsset;
use common\components\Json;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class MediaModal extends Widget
{
    const TYPE_IMAGE = 'image';
    const TYPE_AUDIO = 'audio';
    const TYPE_VIDEO = 'video';
    const TYPE_FILE = 'application';

    public $title = 'Media Files Browser';
    public $post = null;
    public $type = null;
    public $editor = null;
    public $multiple = null;
    public $callback = [
        'name' => '',
        'value' => '',
    ];
    public $buttonTag = 'button';
    public $buttonContent = null;
    public $buttonOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->buttonContent)) {
            $this->buttonContent = '<i class="fa fa-folder-open"></i> ' . Yii::t('writesdown', 'Open Media');
        }

        if ($this->buttonTag == 'button') {
            $this->buttonOptions['type'] = 'button';
        } elseif ($this->buttonTag == 'a' && empty($this->buttonOptions['href'])) {
            $this->buttonOptions['href'] = '#';
        }

        $this->buttonOptions['data-toggle'] = 'media-browser';
        $this->buttonOptions['id'] = $this->id;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

        return Html::tag($this->buttonTag, $this->buttonContent, $this->buttonOptions);
    }

    /**
     * Register needed scrips
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        MediaModalAsset::register($view);

        $callbackName = ArrayHelper::remove($this->callback, 'name');
        $callbackValue = ArrayHelper::remove($this->callback, 'value');

        if ($callbackName) {
            $view->registerJs('var ' . $callbackName . ' = ' . $callbackValue, $view::POS_END);
        }

        $settings['title'] = $this->title;
        $settings['url'] = Url::to([
            'media-browser/index',
            'post' => $this->post,
            'type' => $this->type,
            'editor' => $this->editor,
            'multiple' => $this->multiple,
            'callback' => $callbackName,
        ]);
        $settings = Json::htmlEncode($settings);
        $view->registerJs('$("#' . $this->id . '").mediamodal(' . $settings . ')');
    }
}
