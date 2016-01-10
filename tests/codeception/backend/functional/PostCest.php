<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\functional;

use tests\codeception\backend\_pages\_post\CreatePage;
use tests\codeception\backend\_pages\_post\IndexPage;
use tests\codeception\backend\_pages\_post\UpdatePage;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\FunctionalTester;
use tests\codeception\common\fixtures\PostCommentFixture;
use tests\codeception\common\fixtures\PostFixture;
use tests\codeception\common\fixtures\PostTypeFixture;
use tests\codeception\common\fixtures\PostTypeTaxonomyFixture;
use tests\codeception\common\fixtures\TaxonomyFixture;
use tests\codeception\common\fixtures\TermFixture;
use tests\codeception\common\fixtures\TermRelationshipFixture;
use common\models\Post;
use yii\helpers\Url;

class PostCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param FunctionalTester $I
     */
    public function _before($I)
    {
        $this->loadFixtures();

        $loginPage = LoginPage::openBy($I);
        $loginPage->submit(['username' => 'editor', 'password' => 'editor']);
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

    public function testIndex(FunctionalTester $I)
    {
        $I->wantTo('ensure that index post works');
        $indexPage = IndexPage::openBy($I);
        $I->see('Posts', 'h1');

        $I->amGoingTo('submit search form with non existing post');
        $indexPage->submit(['post_title' => 'non_existing_post']);
        $I->expectTo('not see a record');
        $I->see('No results found.', '#post-grid-view');

        $I->amGoingTo('submit search form with existing post');
        $indexPage->submit(['post_title' => 'post', 'post_slug' => '']);
        $I->expectTo('see post of which the title contains post');
        $I->see('post', '#post-grid-view');
        $I->dontSee('page', '#post-grid-view');
    }

    public function testCreate(FunctionalTester $I)
    {
        $I->wantTo('ensure that create post works');
        $createPage = CreatePage::openBy($I);
        $I->see('Add New Post', 'h1');
        $I->see('Categories');
        $I->see('Tags');

        $I->amGoingTo('submit create post form with no data');
        $createPage->submit([]);
        $I->expectTo('see validation errors');
        $I->see('Title cannot be blank.', '.help-block');

        $I->amGoingTo('submit create post form with same title');
        $createPage->submit([
            'post_title' => 'Sample Post'
        ]);
        $I->expectTo('see that title already taken');
        $I->see('Title "Sample Post" has already been taken.', '.help-block');

        $I->amGoingTo('submit create post form with correct data');
        $createPage->submit([
            'post_title'   => 'New Test Post Title',
            'post_slug'    => 'new-test-post-title',
            'post_content' => 'New Test Post Content'
        ]);
        $I->expect('new post saved');
        $I->see('Post successfully saved.', '.alert');

        Post::deleteAll(['post_title' => 'New Test Post Title']);
    }

    public function testUpdate(FunctionalTester $I)
    {
        $I->wantTo('ensure that update post works');
        $updatePage = UpdatePage::openBy($I);
        $I->see('Update Post', 'h1');
        $I->see('Categories');
        $I->see('Tags');

        $I->amGoingTo('submit post post title same post title');
        $updatePage->submit(['post_title' => 'Sample Page']);
        $I->expectTo('see that post title already taken');
        $I->see('Title "Sample Page" has already been taken.', '.help-block');

        $I->amGoingTo('submit post form with correct data');
        $updatePage->submit([
            'post_title' => 'Sample Post Update'
        ]);
        $I->expect('post updated');
        $I->see('Post successfully saved.', '.alert');

        Post::findOne(1)->updateAttributes(['post_title' => 'Sample Post']);
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->wantTo('ensure that delete post works');
        IndexPage::openBy($I);
        $I->see('Posts', 'h1');

        $I->seeElement('a[href="' . Url::to(['/post/delete', 'id' => 1]) . '"]');

        // Delete only work for acceptance JS
    }

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
