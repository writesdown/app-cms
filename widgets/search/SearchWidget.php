<?php
/**
 * @file      SearchWidget.php
 * @date      9/12/2015
 * @time      3:17 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace widgets\search;

use Yii;
use common\components\BaseWidget;

/* MODEL */
use common\models\Option;

/**
 * Class Search
 *
 * @package widgets\search
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.1
 */
class SearchWidget extends BaseWidget
{
    /**
     * @var string Path of the search form. If there is a file search-form.php in directory 'layouts' of active theme,
     * the widget uses it, but if not, the widget uses search-form.php that exist in the directory 'views'.
     */
    private $_searchForm;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $theme = Option::get('theme');
        $searchForm = Yii::getAlias('@themes/' . $theme . '/layouts/search-form.php');
        if (is_file($searchForm)) {
            $this->_searchForm = $searchForm;
        } else {
            $this->_searchForm = __DIR__ . '/views/search-form.php';
        }
        parent::init();
    }

    public function run()
    {
        echo $this->beforeWidget;
        if ($this->title) {
            echo $this->beforeTitle . $this->title . $this->afterTitle;
        }
        echo Yii::$app->view->renderFile($this->_searchForm);
        echo $this->afterWidget;
    }
}