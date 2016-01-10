<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_user;

use yii\codeception\BasePage;

/**
 * Class UpdatePage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class UpdatePage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/user/update', 'id' => 3];

    /**
     * @param array $data
     */
    public function submit(array $data){
        foreach ($data as $field => $value) {
            $this->actor->fillField('input[name="User[' . $field . ']"]', $value);
        }

        $this->actor->selectOption('#user-role', 'subscriber');
        $this->actor->click('Update', '#user-form');

        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}
