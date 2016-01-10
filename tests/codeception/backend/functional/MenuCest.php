<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\functional;

use common\models\Menu;
use tests\codeception\backend\_pages\_menu\IndexPage;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\FunctionalTester;
use tests\codeception\common\fixtures\MenuFixture;
use tests\codeception\common\fixtures\MenuItemFixture;
use yii\helpers\Url;

/**
 * Class MenuCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class MenuCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param FunctionalTester $I
     */
    public function _before($I)
    {
        $menuFixture = new MenuFixture();
        $menuFixture->load();

        $menuItemFixture = new MenuItemFixture();
        $menuItemFixture->load();

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
    public function testCreateMenu(FunctionalTester $I)
    {
        $I->wantTo('ensure that create menu works');
        $indexPage = IndexPage::openBy($I);
        $I->see('Menus', 'h1');
        $I->see('Menu Primary', 'h2');

        $I->amGoingTo('submit create menu form with no menu title');
        $indexPage->submitMenu('');
        $I->expect('new menu not saved');
        $I->seeCurrentUrlEquals(Url::to(['/menu/index']));

        $I->amGoingTo('submit create menu with menu title');
        $indexPage->submitMenu('Test Menu');
        $I->expectTo('see new menu');
        $I->dontSeeCurrentUrlEquals(Url::to(['/menu/index']));
        $I->see('Test Menu', 'h2');
        $I->dontSee('Menu Item Primary');

        Menu::deleteAll(['menu_title' => 'Test Menu']);
    }

    /**
     * @param FunctionalTester $I
     */
    public function testSelectMenu(FunctionalTester $I)
    {
        $I->wantTo('ensure that select menu works');
        IndexPage::openBy($I);
        $I->see('Menus', 'h1');
        $I->see('Menu Primary', 'h2');

        $I->amGoingTo('submit select menu to Menu Secondary');
        $I->selectOption('#select-menu-list', 'Menu Secondary');
        $I->click('Select', '#select-menu-form');
        $I->expectTo('see Menu Secondary');
        $I->see('Menu Secondary', 'h2');

        $I->click('Save');
        $I->see('Menu successfully saved.', '.alert');
    }

    protected function loadFixtures()
    {
        $menuFixture = new MenuFixture();
        $menuFixture->load();

        $menuItemFixture = new MenuItemFixture();
        $menuItemFixture->load();
    }
}
