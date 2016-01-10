<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\frontend\acceptance;

use common\models\Post;
use common\models\PostComment;
use tests\codeception\common\fixtures\PostTypeFixture;
use tests\codeception\common\fixtures\PostFixture;
use tests\codeception\common\fixtures\PostCommentFixture;
use tests\codeception\common\fixtures\PostTypeTaxonomyFixture;
use tests\codeception\common\fixtures\TaxonomyFixture;
use tests\codeception\common\fixtures\TermFixture;
use tests\codeception\common\fixtures\TermRelationshipFixture;
use tests\codeception\frontend\_pages\PostViewPage;
use tests\codeception\frontend\AcceptanceTester;
use yii\helpers\Url;

/**
 * Class PostCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class PostCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param AcceptanceTester $I
     */
    public function _before($I)
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
    public function testIndex(AcceptanceTester $I)
    {
        $I->wantTo('ensure that index post works');
        $I->amOnPage(Url::to(['/post/index', 'id'=>'1']));
        $I->seeLink('Sample Post');
        $I->click('Sample Post');
        // $I->see('Sample Post', 'h1');
        $I->see('Sample Post');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testView(AcceptanceTester $I){
        $I->wantTo('ensure that post view works');

        $I->amOnPage(Url::to(['/post/view', 'id' => 1]));
        // $I->see('Sample Post', 'h1');
        $I->see('Sample Post');

        $I->amOnPage(Url::to(['/post/view', 'postslug' => 'sample-post']));
        // $I->see('Sample Post', 'h1');
        $I->see('Sample Post');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testProtected(AcceptanceTester $I){
        Post::findOne(1)->updateAttributes(['post_password' => 'postpassword']);

        $I->wantTo('ensure that protected post works');

        $postView = PostViewPage::openBy($I);
        $I->see('Sample Post', 'h1');

        $I->amGoingTo('submit password form with incorrect password');
        $postView->submitPassword('wrong_password');
        $I->expectTo('not see the post');
        // $I->dontSeeElement('.entry-meta');
        $I->see('Submit Password');

        $I->amGoingTo('submit password form with correct password');
        $postView->submitPassword('postpassword');
        $I->expectTo('see the post');
        // $I->seeElement('.entry-meta');
        $I->dontSee('Submit Password');

        Post::findOne(1)->updateAttributes(['post_password' => '']);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testComment(AcceptanceTester $I){
        $I->wantTo('ensure that post comment works');

        $postView = PostViewPage::openBy($I);
        // $I->see('Sample Post', 'h1');
        $I->see('Sample Post');

        $I->amGoingTo('submit post comment form with no data');
        $postView->submitComment([]);
        $I->expectTo('see validations error');
        $I->see('Name cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Content cannot be blank.', '.help-block');

        $I->amGoingTo('submit post comment form with no correct email');
        $postView->submitComment([
            'comment_author'       => 'tester',
            'comment_author_email' => 'tester.email',
            'comment_content'      => 'New comment'
        ]);
        $I->expectTo('see that email is not correct');
        $I->see('Email is not a valid email address.');
        $I->dontSee('Name cannot be blank.', '.help-block');
        $I->dontSee('Content cannot be blank.', '.help-block');

        $I->amGoingTo('submit post comment form with correct data');
        $postView->submitComment([
            'comment_author'       => 'tester',
            'comment_author_email' => 'tester@writesdown.dev',
            'comment_content'      => 'New comment'
        ]);
        $I->expect('new comment saved');
        $I->dontSee('Name cannot be blank.', '.help-block');
        $I->dontSee('Email cannot be blank.', '.help-block');
        $I->dontSee('Content cannot be blank.', '.help-block');

        PostComment::deleteAll(['comment_author'=>'tester']);
        Post::findOne(1)->updateAttributes(['post_comment_count' => '1']);
    }
}
