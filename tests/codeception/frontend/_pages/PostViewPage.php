<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\frontend\_pages;

use yii\codeception\BasePage;

/**
 * Class PostViewPage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class PostViewPage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/post/view', 'id' => 1];

    /**
     * @param string $password
     */
    public function submitPassword($password)
    {
        $this->actor->fillField('password', $password);
        $this->actor->click('Submit Password');

        // For selenium only
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }

    /**
     * @param array $data
     */
    public function submitComment(array $data)
    {
        foreach ($data as $field => $value) {
            $inputType = $field === 'comment_content' ? 'textarea' : 'input';
            $this->actor->fillField($inputType . '[name="PostComment[' . $field . ']"]', $value);
        }

        $this->actor->click('#respond button[type="submit"]');

        // For selenium only
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }
}
