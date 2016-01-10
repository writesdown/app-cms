<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\functional;

use common\models\Module;
use tests\codeception\backend\_pages\_module\IndexPage;
use tests\codeception\backend\_pages\_module\UpdatePage;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\FunctionalTester;
use tests\codeception\common\fixtures\ModuleFixture;
use yii\helpers\Url;

/**
 * Class ModuleCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class ModuleCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param FunctionalTester $I
     */
    public function _before($I)
    {
        $moduleFixture = new ModuleFixture();
        $moduleFixture->load();

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
        $I->wantTo('ensure that index module works');
        $indexPage = IndexPage::openBy($I);
        $I->see('Modules', 'h1');


        $I->amGoingTo('submit search form with non existing module');
        $indexPage->submit(['module_name' => 'non_existing_module']);
        $I->expectTo('not see a record');
        $I->see('No results found.', '#module-grid-view');

        $I->amGoingTo('submit search form with existing module');
        $indexPage->submit(['module_name' => 'sitemap']);
        $I->expectTo('see modules of which name are sitemap');
        $I->see('sitemap', '#module-grid-view');
        $I->dontSee('feed', '#module-grid-view');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testCreate(FunctionalTester $I)
    {
        $I->wantTo('ensure that create module works');
        $I->amOnPage(Url::to(['/module/create']));
        $I->see('Add New Module', 'h1');
        $I->see('Upload New Module');
        $I->see('Upload', '#module-create-form');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testUpdate(FunctionalTester $I)
    {
        $I->wantTo('ensure that update page works');
        $updatePage = UpdatePage::openBy($I);
        $I->see('Update Module: sitemap', 'h1');
        $I->seeLink('Basic', '#');
        $I->seeLink('Frontend', '#');
        $I->seeLink('Backend', '#');

        $I->amGoingTo('submit update module form with correct data');
        $I->checkOption('#module-module_status');
        $updatePage->submit([
            'module_name'  => 'test',
            'module_title' => 'Test',
        ]);
        $I->expect('module updated');
        $I->see('Modules', 'h1');
        $I->see('Test', '#module-grid-view');

        Module::findOne(2)->updateAttributes([
            'module_name'   => 'sitemap',
            'module_title'  => 'Sitemap',
            'module_status' => '0'
        ]);
    }
}
