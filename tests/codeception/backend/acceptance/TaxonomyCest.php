<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\acceptance;

use common\models\search\Taxonomy;
use common\models\Term;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\_pages\_taxonomy\CreatePage;
use tests\codeception\backend\_pages\_taxonomy\IndexPage;
use tests\codeception\backend\_pages\_taxonomy\UpdatePage;
use tests\codeception\backend\_pages\_taxonomy\UpdateTermPage;
use tests\codeception\backend\_pages\_taxonomy\ViewPage;
use tests\codeception\backend\AcceptanceTester;
use tests\codeception\common\fixtures\PostCommentFixture;
use tests\codeception\common\fixtures\PostFixture;
use tests\codeception\common\fixtures\PostTypeFixture;
use tests\codeception\common\fixtures\PostTypeTaxonomyFixture;
use tests\codeception\common\fixtures\TaxonomyFixture;
use tests\codeception\common\fixtures\TermFixture;
use tests\codeception\common\fixtures\TermRelationshipFixture;
use yii\helpers\Url;

/**
 * Class TaxonomyCest
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.2
 */
class TaxonomyCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param AcceptanceTester $I
     */
    public function _before($I)
    {
        $this->loadFixtures();

        $loginPage = LoginPage::openBy($I);
        $loginPage->submit(['username' => 'administrator', 'password' => 'administrator']);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testIndex(AcceptanceTester $I)
    {
        $I->wantTo('ensure that taxonomy index works');
        $indexPage = IndexPage::openBy($I);
        $I->see('Taxonomies', 'h1');

        $I->amGoingTo('submit search form with non existing taxonomy');
        $indexPage->submit(['name' => 'non_existing_taxonomy']);
        $I->expectTo('not see a record');
        $I->see('No results found.', '#taxonomy-grid-view');

        $I->amGoingTo('submit search form with existing taxonomy');
        $indexPage->submit(['name' => 'category', 'slug' => '']);
        $I->expectTo('see taxonomy of which the name is category');
        $I->see('category', '#taxonomy-grid-view');
        $I->dontSee('tag', '#taxonomy-grid-view');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testView(AcceptanceTester $I)
    {
        $I->wantTo('ensure that view taxonomy works');
        $viewPage = ViewPage::openBy($I);
        $I->see('View Taxonomy: Tag', 'h1');

        $I->amGoingTo('submit search term form with non existing term');
        $viewPage->submitSearchTerm(['name' => 'non_existing_term']);
        $I->expectTo('not see term(s) on term grid view');
        $I->dontSee('Sample Tag', '#term-grid-view');

        $I->amGoingTo('submit create term form with no data');
        $viewPage->submitCreateTerm([]);
        $I->expectTo('see validation errors');
        $I->see('Name cannot be blank.', '.help-block');

        $I->amGoingTo('submit create term form with same data');
        $viewPage->submitCreateTerm([
            'name' => 'Sample Tag',
            'slug' => 'sample-tag',
        ]);
        $I->expectTo('see validation errors');
        $I->see('Name "Sample Tag" has already been taken.', '.help-block');
        $I->see('Slug "sample-tag" has already been taken.', '.help-block');

        $I->amGoingTo('submit create term form with correct data');
        $viewPage->submitCreateTerm([
            'name' => 'Sample Tag 1',
            'slug' => '',
        ]);
        $I->expect('new tag saved');
        $I->see('Sample Tag 1', '#term-grid-view');

        Term::deleteAll(['name' => 'Sample Tag 1']);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testCreate(AcceptanceTester $I)
    {
        $I->wantTo('ensure that create taxonomy works');
        $createPage = CreatePage::openBy($I);
        $I->see('Add New Taxonomy');

        $I->amGoingTo('submit taxonomy form with empty data');
        $createPage->submit([]);
        $I->expectTo('see error messages');
        $I->see('Name cannot be blank.', '.help-block');
        $I->see('Singular Name cannot be blank.', '.help-block');
        $I->see('Plural Name cannot be blank.', '.help-block');

        $I->amGoingTo('submit taxonomy form with already stored data');
        $createPage->submit([
            'name' => 'category',
            'slug' => 'category',
            'singular_name' => 'Category',
            'plural_name' => 'Categories',
        ]);
        $I->expectTo('see error messages');
        $I->see('Name "category" has already been taken.', '.help-block');
        $I->see('Slug "category" has already been taken.', '.help-block');

        $I->amGoingTo('submit taxonomy with correct');
        $createPage->submit([
            'name' => 'test',
            'slug' => 'test',
            'singular_name' => 'Test',
            'plural_name' => 'Tests',
        ]);
        $I->expect('new taxonomy saved');
        $I->see('View Taxonomy: Test', 'h1');

        Taxonomy::deleteAll(['name' => 'test']);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testUpdate(AcceptanceTester $I)
    {
        $I->wantTo('ensure that update taxonomy works');
        $updateTaxonomy = UpdatePage::openBy($I);
        $I->see('Update Taxonomy: Tag', 'h1');

        $I->amGoingTo('submit update taxonomy form with correct data');
        $updateTaxonomy->submit([
            'singular_name' => 'New Taxonomy',
            'plural_name' => 'New Taxonomies',
        ]);
        $I->expect('taxonomy updated');

        $I->see('View Taxonomy: New Taxonomy');
        $I->seeElement('#term-parent');

        Taxonomy::findOne(2)->update([
            'hierarchical' => '0',
            'menu_builder' => '0',
            'singular_name' => 'Tag',
            'plural_name' => 'Tags',
        ]);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testDelete(AcceptanceTester $I)
    {
        $I->wantTo('ensure that delete taxonomy works');
        IndexPage::openBy($I);
        $I->see('Taxonomies', 'h1');

        $I->seeElement('a[href="' . Url::to(['/taxonomy/delete', 'id' => 1]) . '"]');

        if (method_exists($I, 'acceptPopup') && method_exists($I, 'wait')) {
            $I->click('a[href="' . Url::to(['/taxonomy/delete', 'id' => 1]) . '"]');
            $I->acceptPopup();
            $I->wait(3);

            $I->dontSee('Category', '#taxonomy-grid-view');

            $this->loadFixtures();
        }
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testUpdateTerm(AcceptanceTester $I)
    {
        $I->wantTo('ensure that update term works');
        $updateTermPage = UpdateTermPage::openBy($I);
        $I->see('View Taxonomy: Tag');

        $I->amGoingTo('submit update term form');
        $updateTermPage->submit([
            'slug' => 'new-sample-tag-slug',
            'description' => 'New sample tag description',
        ]);
        $I->expectTo('see the term updated');
        $I->see('New sample tag description', '#term-grid-view');
        $I->see('new-sample-tag-slug', '#term-grid-view');

        Term::findOne(2)->updateAttributes([
            'description' => 'This is sample tag description',
            'slug' => 'sample-tag hlhl',
        ]);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testDeleteTerm(AcceptanceTester $I)
    {
        $I->wantTo('ensure that delete term works');
        ViewPage::openBy($I);
        $I->see('View Taxonomy', 'h1');

        $I->seeElement('a[href="' . Url::to(['/taxonomy/delete-term', 'id' => 2, 'term' => 2]) . '"]');

        if (method_exists($I, 'acceptPopup') && method_exists($I, 'wait')) {
            $I->click('a[href="' . Url::to(['/taxonomy/delete-term', 'id' => 2, 'term' => 2]) . '"]');
            $I->acceptPopup();
            $I->wait(3);

            $I->dontSee('Sample Tag', '#term-grid-view');

            $this->loadFixtures();
        }
    }

    /**
     * Load needed fixtures
     */
    protected function loadFixtures()
    {
        $postTypeFixture = new PostTypeFixture();
        $postTypeFixture->load();

        $taxonomyFixture = new TaxonomyFixture();
        $taxonomyFixture->load();

        $postTypeTaxonomyFixture = new PostTypeTaxonomyFixture();
        $postTypeTaxonomyFixture->load();

        $termFixture = new TermFixture();
        $termFixture->load();

        $postFixture = new PostFixture();
        $postFixture->load();

        $termRelationshipFixture = new TermRelationshipFixture();
        $termRelationshipFixture->load();

        $postCommentFixture = new PostCommentFixture();
        $postCommentFixture->load();
    }
}
