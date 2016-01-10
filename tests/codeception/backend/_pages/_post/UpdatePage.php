<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_post;

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
    public $route = ['/post/update', 'id' => '1'];

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        foreach ($data as $field => $value) {
            $fieldType = $field == 'post_content' ? 'textarea' : 'input';
            $this->actor->fillField($fieldType . '[name="Post[' . $field . ']"]', $value);
        }

        $this->actor->click('Publish', '#post-update-form');

        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }
}