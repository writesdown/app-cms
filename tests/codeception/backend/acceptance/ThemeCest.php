<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\acceptance;

use common\models\Option;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\AcceptanceTester;
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
     * @param AcceptanceTester $I
     */
    public function _before($I)
    {
        $loginPage = LoginPage::openBy($I);
        $loginPage->submit(['username' => 'administrator', 'password' => 'administrator']);
    }

    /**
     * This method is called after each cest class test method, even if test failed.
     *
     * @param AcceptanceTester $I
     */
    public function _after($I)
    {
    }

    /**
     * This method is called when test fails.
     *
     * @param AcceptanceTester $I
     */
    public function _failed($I)
    {
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testIndex(AcceptanceTester $I){
        $I->wantTo('ensure that index theme works');
        $I->amOnPage(Url::to(['/theme/index']));
        $I->see('Themes','h1');
        $I->seeLink('Available Themes');
        $I->seeLink('Add New Theme');

        if(method_exists($I, 'acceptPopup') && method_exists($I, 'wait')){
            $I->amGoingTo('activate theme');
            $I->click('a[href="' . Url::to(['/theme/install', 'theme' => 'writesdown']) . '"]');
            $I->acceptPopup();
            $I->wait(3);
            $I->expectTo('see theme installed');
            $I->see('Installed');

            Option::up('theme', 'default');
        }
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testUpload(AcceptanceTester $I){
        $I->wantTo('ensure that upload theme works');
        $I->amOnPage(Url::to(['/theme/upload']));
        $I->see('Upload New Theme', 'h1');
        $I->seeLink('Available Themes');
        $I->seeLink('Add New Theme');
        $I->see('Upload');

        $I->amGoingTo('submit theme form without theme file');
        $I->click('Upload');
        if(method_exists($I, 'wait')){
            $I->wait(3);
        }
        $I->expectTo('see validation errors');
        $I->see('Theme cannot be blank.', '.help-block');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testDetail(AcceptanceTester $I){
        $I->wantTo('ensure that detail theme works');
        $I->amOnPage(Url::to(['/theme/detail', 'theme' => 'writesdown']));
        $I->see('Detail Theme', 'h1');
        $I->see('Name');
        $I->see('Author');
    }
}
