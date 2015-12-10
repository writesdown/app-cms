<?php
/**
 * @file      UpdateTerm.php
 * @date      12/7/2015
 * @time      1:46 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\backend\_pages\_taxonomy;


use yii\codeception\BasePage;

/**
 * Class UpdateTermPage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @package tests\codeception\backend\_pages\_taxonomy
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class UpdateTermPage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/taxonomy/update-term', 'id' => 2, 'term_id' => 2];

    /**
     * @param array $data
     */
    public function submit(array $data){
        foreach ($data as $field => $value) {
            $inputType = $field == 'term_description' ? 'textarea' : 'input';
            $this->actor->fillField('#term-form ' . $inputType . '[name="Term[' . $field . ']"]', $value);
        }
        $this->actor->click('Update', '#term-form');

        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}