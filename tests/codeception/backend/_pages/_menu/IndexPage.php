<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_menu;

use yii\codeception\BasePage;

/**
 * Class IndexPage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class IndexPage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/menu/index'];

    /**
     * @param string $title
     */
    public function submitMenu($title){
        $this->actor->fillField('#create-menu-form input[name="Menu[menu_title]"]', $title);
        $this->actor->click('Add New Menu', '#create-menu-form');

        if(method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }

    /**
     * @param array $data
     */
    public function submitMenuItem(array $data){
        foreach ($data as $field => $value) {
            $this->actor->fillField('#link input[name="MenuItem[' . $field . ']"]', $value);
        }

        $this->actor->click('Add Menu', '#link');

        if(method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }

    }
}
