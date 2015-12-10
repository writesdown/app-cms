<?php
/**
 * @file      UpdatePage.php
 * @date      12/9/2015
 * @time      4:55 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\backend\_pages\_mediacomment;


use yii\codeception\BasePage;

/**
 * Class UpdatePage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @package tests\codeception\backend\_pages\_mediacomment
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class UpdatePage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/media-comment/update', 'id' => '1'];

    /**
     * @param array $data
     */
    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        foreach ($data as $field => $value) {
            if ($field == 'comment_content') {
                // Run js for TinyMCE
                if (method_exists($this->actor, 'executeJS')) {
                    $this->actor->executeJS('$("#mediacomment-comment_content").val("' . $value . '")');
                } else {
                    $this->actor->fillField('textarea[name="MediaComment[comment_content]"]', $value);
                }
            } else {
                $this->actor->fillField('input[name="MediaComment[' . $field . ']"]', $value);
            }
        }
        $this->actor->click('Update', '#media-comment-update-form');

        // Wait to submit
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }
}