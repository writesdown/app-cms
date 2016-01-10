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
 * Class WidgetCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class WidgetCest
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
    public function testIndex(FunctionalTester $I)
    {
        $I->wantTo('ensure that widget works');
        $I->amOnPage(Url::to(['/widget/index']));
        $I->see('Widgets', 'h1');
        $I->see('Available Widgets', 'h4');
        $I->see('Sidebar', 'h3');
        $I->seeLink('Add New Widget');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testCreate(FunctionalTester $I)
    {
        $I->wantTo('ensure that create widget works');
        $I->amOnPage(Url::to(['/widget/create']));
        $I->see('Add New Widget', 'h1');
        $I->see('Upload New Widget');
        $I->see('Upload', '#widget-create-form');
    }
}
