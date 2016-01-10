<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_posttype;

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
    public $route = ['/post-type/create'];

    /**
     * @param array $data
     */
    public function submitPostType(array $data){
        foreach ($data as $field => $value) {
            $fieldType = $field == 'post_type_description' ? 'textarea' : 'input';
            $this->actor->fillField($fieldType . '[name="PostType[' . $field . ']"]', $value);
        }

        // $this->actor->click('Save', '#post-type-form');
        $this->actor->click('#post-type-form button[type="submit"]');

        // Wait to submit
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }

    public function submitTaxonomy(array $data){
        foreach ($data as $field => $value) {
            $this->actor->fillField('input[name="Taxonomy[' . $field . ']"]', $value);
        }

        $this->actor->click('Add New Taxonomy', '#ajax-create-taxonomy-form');

        // Wait to submit
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}
