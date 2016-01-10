<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_taxonomy;

use yii\codeception\BasePage;

/**
 * Class ViewPage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class ViewPage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/taxonomy/view', 'id' => 2];

    /**
     * @param array $data
     */
    public function submitCreateTerm(array $data){
        foreach ($data as $field => $value) {
            $inputType = $field == 'term_description' ? 'textarea' : 'input';
            $this->actor->fillField('#term-form ' . $inputType . '[name="Term[' . $field . ']"]', $value);
        }

        $this->actor->click('button[type="submit"]', '#term-form');

        // Wait
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }

    /**
     * @param array $data
     */
    public function submitSearchTerm(array $data){
        $this->actor->click('button[data-target="#term-search"]');

        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }

        foreach ($data as $field => $value) {
            $this->actor->fillField('#term-search input[name="Term[' . $field . ']"]', $value);
        }

        $this->actor->click('Search', '#term-search');

        // Wait
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}
