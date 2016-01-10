<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\frontend\functional;

use common\models\Post;
use tests\codeception\common\fixtures\PostCommentFixture;
use tests\codeception\common\fixtures\PostFixture;
use tests\codeception\common\fixtures\PostTypeFixture;
use tests\codeception\common\fixtures\PostTypeTaxonomyFixture;
use tests\codeception\common\fixtures\TaxonomyFixture;
use tests\codeception\common\fixtures\TermFixture;
use tests\codeception\common\fixtures\TermRelationshipFixture;
use tests\codeception\frontend\_pages\PostViewPage;
use tests\codeception\frontend\FunctionalTester;
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
     * @param FunctionalTester $I
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
        $I->wantTo('ensure that index post works');
        $I->amOnPage(Url::to(['/post/index', 'id'=>'1']));
        $I->seeLink('Sample Post');
        $I->click('Sample Post');
        $I->see('Sample Post', 'h1');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testView(FunctionalTester $I){
        $I->wantTo('ensure that post view works');

        $I->amOnPage(Url::to(['/post/view', 'id' => 1]));
        $I->see('Sample Post', 'h1');

        $I->amOnPage(Url::to(['/post/view', 'postslug' => 'sample-post']));
        $I->see('Sample Post', 'h1');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testProtected(FunctionalTester $I){
        Post::findOne(1)->updateAttributes(['post_password' => 'postpassword']);

        $I->wantTo('ensure that protected post works');

        $postView = PostViewPage::openBy($I);
        $I->see('Sample Post', 'h1');

        $I->amGoingTo('submit password form with incorrect password');
        $postView->submitPassword('wrong_password');
        $I->expectTo('not see the post');
        $I->dontSeeElement('.entry-meta');

        $I->amGoingTo('submit password form with correct password');
        $postView->submitPassword('postpassword');
        $I->expectTo('see the post');
        $I->seeElement('.entry-meta');

        Post::findOne(1)->updateAttributes(['post_password' => '']);
    }
}
