<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\functional;

use common\models\Option;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\FunctionalTester;
use tests\codeception\common\fixtures\OptionFixture;
use yii\helpers\Url;

class SettingCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param FunctionalTester $I
     */
    public function _before($I)
    {
        $optionFixture = new OptionFixture();
        $optionFixture->load();

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

    public function testIndex(FunctionalTester $I){
        $I->wantTo('ensure that index page only available for superadmin');
        $I->amOnPage(Url::to(['/setting/index']));
        $I->see('Forbidden (#403)', 'h1');
        $I->see('You are not allowed to perform this action.', '.alert');
    }

    public function testGroup(FunctionalTester $I){
        $I->wantTo('ensure that setting works');
        $I->amOnPage(Url::to(['/setting/group', 'id' => 'general']));
        $I->see('General Settings', 'h1');

        $I->amGoingTo('update site title and tag line');
        $I->fillField('input[name="Option[sitetitle][option_value]"]', 'My New Website');
        $I->fillField('input[name="Option[tagline][option_value]"]', 'My New Website Tagline');
        $I->click('Save');
        $I->expectTo('see success message');

        $I->see('Settings successfully saved.');

        Option::up('sitetitle', 'WritesDown');
        Option::up('tagline', 'CMS Built with Yii Framework');
    }
}
