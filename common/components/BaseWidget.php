<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\components;

use yii\base\Object;

/**
 * Class BaseWidget
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
abstract class BaseWidget extends Object
{
    /**
     * @var integer Id of active widget that can be used for id of HTML element.
     */
    public $id;
    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $beforeTitle = '';

    /**
     * @var string
     */
    public $afterTitle = '';

    /**
     * @var string
     */
    public $beforeWidget = '';

    /**
     * @var string
     */
    public $afterWidget = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->run();
    }

    /**
     * Executes the widget.
     */
    public function run()
    {
    }
}
