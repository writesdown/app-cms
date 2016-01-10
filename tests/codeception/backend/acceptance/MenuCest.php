<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\acceptance;

use common\models\Menu;
use common\models\MenuItem;
use tests\codeception\backend\_pages\_menu\IndexPage;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\AcceptanceTester;
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
     * @param AcceptanceTester $I
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
    public function testCreateMenu(AcceptanceTester $I)
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
     * @param AcceptanceTester $I
     */
    public function testSelectMenu(AcceptanceTester $I)
    {
        $I->wantTo('ensure that select menu item works');
        IndexPage::openBy($I);
        $I->see('Menus', 'h1');
        $I->see('Menu Primary', 'h2');

        $I->selectOption('#select-menu-list', 'Menu Secondary');
        // $I->click('Select', '#select-menu-form');
        $I->click('#select-menu-form button[type="submit"]');
        if(method_exists($I, 'wait')){
            $I->wait(3);
        }
        $I->see('Menu Secondary', 'h2');

        $I->click('Save');
        if(method_exists($I, 'wait')){
            $I->wait(3);
        }
        $I->see('Menu successfully saved.', '.alert');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testCreateMenuItem(AcceptanceTester $I)
    {
        $I->wantTo('ensure that create menu item works');
        $indexPage = IndexPage::openBy($I);
        $I->see('Menus', 'h1');
        $I->see('Menu Primary', 'h2');

        if(method_exists($I, 'wait')){
            $I->amGoingTo('submit menu form with correct data');
            $indexPage->submitMenuItem([
                'menu_label' => 'New Menu Item',
                'menu_url'   => 'http://writesdown.com'
            ]);
            $I->expectTo('see new menu item');
            $I->see('New Menu Item', '.dd-handle');
        }

        MenuItem::deleteAll(['menu_label' => 'Test Menu Item']);
    }
}
