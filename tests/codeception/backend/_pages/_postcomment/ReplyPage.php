<?php
/**
 * @file      ReplyPage.php
 * @date      12/8/2015
 * @time      9:27 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\backend\_pages\_postcomment;


use yii\codeception\BasePage;

/**
 * Class ReplyPage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @package tests\codeception\backend\_pages\_postcomment
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class ReplyPage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/post-comment/reply', 'id' => 1];

    /**
     * @param string $content
     */
    public function submit($content = null){
        // Run js for TinyMCE
        if(method_exists($this->actor,'executeJS')){
            $this->actor->executeJS('$("#postcomment-comment_content").val("' . $content . '")');
        }else{
            $this->actor->fillField('textarea[name="PostComment[comment_content]"]', $content);
        }
        $this->actor->click('Reply', '#post-comment-reply-form');

        // Wait to submit
        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}