<?php
/**
 * @file      IndexPage.php
 * @date      12/6/2015
 * @time      11:55 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\backend\_pages\_user;


use yii\codeception\BasePage;

/**
 * Class IndexPage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @package tests\codeception\backend\_pages\_user
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class IndexPage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/user/index'];

    /**
     * @param array $data
     */
    public function submit(array $data){
        $this->actor->click('button[data-target="#user-search"]');

        // Wait to toggle
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }

        foreach ($data as $field => $value) {
            $this->actor->fillField('#user-search input[name="User[' . $field . ']"]', $value);
        }
        $this->actor->click('Search', '#user-search');

        // Wait for submitting
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}