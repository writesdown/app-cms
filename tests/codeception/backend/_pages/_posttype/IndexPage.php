<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_posttype;

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
    public $route = ['/post-type/index'];

    /**
     * @param array $data
     */
    public function submit(array $data){
        $this->actor->click('button[data-target="#post-type-search"]');

        // Wait to toggle
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }

        foreach ($data as $field => $value) {
            $this->actor->fillField('#post-type-search input[name="PostType[' . $field . ']"]', $value);
        }

        $this->actor->click('Search', '#post-type-search');

        // Wait to submit
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}
