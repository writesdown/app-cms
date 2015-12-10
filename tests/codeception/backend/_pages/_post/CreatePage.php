<?php
/**
 * @file      CreatePage.php
 * @date      12/7/2015
 * @time      9:55 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\backend\_pages\_post;


use yii\codeception\BasePage;

/**
 * Class CreatePage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @package tests\codeception\backend\_pages\_post
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class CreatePage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/post/create', 'post_type' => '1'];

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        foreach ($data as $field => $value) {
            if ($field == 'post_content') {
                // Execute js for TinyMCE
                if (method_exists($this->actor, 'executeJS')) {
                    $this->actor->executeJS('$("#post-post_content").val("' . $value . '")');
                } else {
                    $this->actor->fillField('textarea[name="Post[post_content]"]', $value);
                }
            } else {
                $this->actor->fillField('input[name="Post[' . $field . ']"]', $value);
            }
        }
        $this->actor->click('Publish', '#post-create-form');

        // Wait to submit
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }
}