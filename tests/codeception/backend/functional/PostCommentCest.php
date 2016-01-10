<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\functional;

use common\models\PostComment;
use tests\codeception\backend\_pages\_postcomment\IndexPage;
use tests\codeception\backend\_pages\_postcomment\ReplyPage;
use tests\codeception\backend\_pages\_postcomment\UpdatePage;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\FunctionalTester;
use tests\codeception\common\fixtures\PostCommentFixture;
use tests\codeception\common\fixtures\PostFixture;
use tests\codeception\common\fixtures\PostTypeFixture;
use tests\codeception\common\fixtures\PostTypeTaxonomyFixture;
use tests\codeception\common\fixtures\TaxonomyFixture;
use tests\codeception\common\fixtures\TermFixture;
use tests\codeception\common\fixtures\TermRelationshipFixture;
use yii\helpers\Url;

/**
 * Class PostCommentCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class PostCommentCest
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

    /**
     * @param FunctionalTester $I
     */
    public function testIndex(FunctionalTester $I)
    {
        $I->wantTo('ensure that index post-comment works');
        $indexPage = IndexPage::openBy($I);
        $I->see('Post Comments', 'h1');

        $I->amGoingTo('submit search form with non existing post-comment');
        $indexPage->submit(['comment_author' => 'non_existing_post_comment']);
        $I->expectTo('not see a record');
        $I->see('No results found.', '#post-comment-grid-view');

        $I->amGoingTo('submit search form with existing post');
        $indexPage->submit(['comment_author' => 'Mr']);
        $I->expectTo('see post-comment of which the author contains Mr');
        $I->see('Mr. WritesDown', '#post-comment-grid-view');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testUpdate(FunctionalTester $I)
    {
        $I->wantTo('ensure that update post-comment works');
        $updatePage = UpdatePage::openBy($I);
        $I->see('Update Post Comment: 1', 'h1');

        $I->amGoingTo('submit post-comment with no correct email & url');
        $updatePage->submit([
            'comment_author'       => 'Tester',
            'comment_author_email' => 'tester.author@test',
            'comment_author_url'   => 'http://.com'
        ]);
        $I->expectTo('see that email & url not correct');
        $I->see('Email is not a valid email address.', '.help-block');
        $I->see('URL is not a valid URL.', '.help-block');

        $I->amGoingTo('submit post-comment with correct data');
        $updatePage->submit([
            'comment_author'       => 'Tester',
            'comment_author_email' => 'tester@tester.com',
            'comment_author_url'   => 'http://tester.com'
        ]);
        $I->expect('post-comment updated');
        $I->dontSee('Email is not a valid email address.', '.help-block');
        $I->dontSee('URL is not a valid URL.', '.help-block');

        PostComment::findOne(1)->updateAll([
            'comment_author'       => 'Mr. WritesDown',
            'comment_author_email' => 'wd@writesdown.com',
            'comment_author_url'   => 'http://www.writesdown.com'
        ]);
    }

    /**
     * @param FunctionalTester $I
     */
    public function testDelete(FunctionalTester $I)
    {
        $I->wantTo('ensure that delete post-comment works');
        IndexPage::openBy($I);
        $I->see('Post Comments');

        $I->seeElement('a[href="' . Url::to(['/post-comment/delete', 'id' => 1]) . '"]');

        // Delete post-comment only work for acceptance JS
    }

    /**
     * @param FunctionalTester $I
     */
    public function testReply(FunctionalTester $I)
    {
        $I->wantTo('ensure that reply post-comment works');
        $replyPage = ReplyPage::openBy($I);

        $I->amGoingTo('reply post-comment with no content');
        $replyPage->submit();
        $I->expectTo('see validation error');
        $I->see('Content cannot be blank.', '.help-block');

        // Reply only work for acceptance $_POST['REMOTE_ADDR']
    }

    /**
     * Load default fixtures for testing
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
