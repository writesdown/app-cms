<?php
/**
 * @file      UpdatePage.php
 * @date      12/8/2015
 * @time      9:06 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\backend\_pages\_postcomment;


use yii\codeception\BasePage;

/**
 * Class UpdatePage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @package tests\codeception\backend\_pages\_postcomment
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class UpdatePage extends BasePage
{
    /**
     * @var array
     */
    public $route = ['/post-comment/update', 'id' => 1];

    /**
     * @param array $data
     */
    public function submit(array $data){
        foreach ($data as $field => $value) {
            if($field == 'comment_content'){
                if(method_exists($this->actor,'executeJS')){
                    $this->actor->executeJS('$("#postcomment-comment_content").val("' . $value . '")');
                }else{
                    $this->actor->fillField('textarea[name="PostComment[comment_content]"]', $value);
                }
            }else{
                $this->actor->fillField('input[name="PostComment[' . $field . ']"]', $value);
            }
        }
        $this->actor->click('Update', '#post-comment-update-form');

        if (method_exists($this->actor, 'wait')){
            $this->actor->wait(3);
        }
    }
}