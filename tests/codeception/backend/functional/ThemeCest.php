<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\functional;

use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\FunctionalTester;
use yii\helpers\Url;

/**
 * Class ThemeCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class ThemeCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param FunctionalTester $I
     */
    public function _before($I)
    {
        $loginPage = LoginPage::openBy($I);
        $loginPage->submit(['username' => 'administrator', 'password' => 'administrator']);
    }

    /**
     * This method is called after each cest class test method, even if test failed.
     *
     * @param FunctionalTester $I
     */
    public function _after($I)
    {
    }

    /**
     * This method is called when test fails.
     *
     * @param FunctionalTester $I
     */
    public function _failed($I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function testIndex(FunctionalTester $I){
        $I->wantTo('ensure that index theme works');
        $I->amOnPage(Url::to(['/theme/index']));
        $I->see('Themes','h1');
        $I->seeLink('Available Themes');
        $I->seeLink('Add New Theme');

        // Activate only work on acceptance tester [JS]
    }

    /**
     * @param FunctionalTester $I
     */
    public function testUpload(FunctionalTester $I){
        $I->wantTo('ensure that upload theme works');
        $I->amOnPage(Url::to(['/theme/upload']));
        $I->see('Upload New Theme', 'h1');
        $I->seeLink('Available Themes');
        $I->seeLink('Add New Theme');
        $I->click('Upload', '#theme-upload-form');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testDetail(FunctionalTester $I){
        $I->wantTo('ensure that detail theme works');
        $I->amOnPage(Url::to(['/theme/detail', 'theme' => 'writesdown']));
        $I->see('Detail Theme', 'h1');
        $I->see('Name');
        $I->see('Author');
    }
}
