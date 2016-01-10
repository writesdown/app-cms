<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_user;

use yii\codeception\BasePage;

/**
 * Class CreatePage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class CreatePage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/user/create'];

    /**
     * @param array $data
     */
    public function submit(array $data){
        foreach ($data as $field => $value) {
            $this->actor->fillField('input[name="User[' . $field . ']"]', $value);
        }

        $this->actor->selectOption('#user-role', 'author');
        $this->actor->click('Save', '#user-form');

        // Wait
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}
