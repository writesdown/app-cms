<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_module;

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
    public $route = ['/module/index'];

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        $this->actor->click('button[data-target="#module-search"]');

        // Wait to toggle
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }

        foreach ($data as $field => $value) {
            $this->actor->fillField('#module-search input[name="Module[' . $field . ']"]', $value);
        }

        $this->actor->click('Search', '#module-search');

        // Wait to submit
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }
}
