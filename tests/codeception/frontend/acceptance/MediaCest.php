<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\frontend\acceptance;

use common\models\Media;
use common\models\MediaComment;
use tests\codeception\common\fixtures\MediaCommentFixture;
use tests\codeception\common\fixtures\MediaFixture;
use tests\codeception\common\fixtures\MediaMetaFixture;
use tests\codeception\frontend\_pages\MediaViewPage;
use tests\codeception\frontend\AcceptanceTester;
use yii\helpers\Url;

/**
 * Class MediaCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class MediaCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param AcceptanceTester $I
     */
    public function _before($I)
    {
        $mediaFixture = new MediaFixture();
        $mediaFixture->load();

        $mediaMetaFixture = new MediaMetaFixture();
        $mediaMetaFixture->load();

        $mediaCommentFixture = new MediaCommentFixture();
        $mediaCommentFixture->load();
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
    public function testView(AcceptanceTester $I)
    {
        $I->wantTo('ensure that view media works');

        $I->amOnPage(Url::to(['/media/view', 'id' => 1]));
        // $I->see('Test Media', 'h1');
        $I->see('Test Media');
        $I->seeLink('Test Media');

        $I->amOnPage(Url::to(['/media/view', 'mediaslug' => 'test-media']));
        // $I->see('Test Media', 'h1');
        $I->see('Test Media');
        $I->seeLink('Test Media');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testProtected(AcceptanceTester $I)
    {
        Media::findOne(1)->updateAttributes(['media_password' => 'mediapassword']);

        $I->wantTo('ensure that protected media works');

        $mediaView = MediaViewPage::openBy($I);
        $I->see('Test Media', 'h1');

        $I->amGoingTo('submit password form with incorrect password');
        $mediaView->submitPassword('wrong_password');
        $I->expectTo('not see the media');
        // $I->dontSeeElement('.entry-meta');
        $I->see('Submit Password');

        $I->amGoingTo('submit password form with correct password');
        $mediaView->submitPassword('mediapassword');
        $I->expectTo('see the post');
        // $I->seeElement('.entry-meta');
        $I->dontSee('Submit Password');

        Media::findOne(1)->updateAttributes(['media_password' => '']);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testComment(AcceptanceTester $I)
    {
        $I->wantTo('ensure that media comment works');

        $mediaView = MediaViewPage::openBy($I);
        // $I->see('Test Media', 'h1');
        $I->see('Test Media');

        $I->amGoingTo('submit media comment form with no data');
        $mediaView->submitComment([]);
        $I->expectTo('see validations error');
        $I->see('Name cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Content cannot be blank.', '.help-block');

        $I->amGoingTo('submit media comment form with no correct email');
        $mediaView->submitComment([
            'comment_author'       => 'tester',
            'comment_author_email' => 'tester.email',
            'comment_content'      => 'New comment'
        ]);
        $I->expectTo('see that email is not correct');
        $I->see('Email is not a valid email address.');
        $I->dontSee('Name cannot be blank.', '.help-block');
        $I->dontSee('Content cannot be blank.', '.help-block');

        $I->amGoingTo('submit media comment form with correct data');
        $mediaView->submitComment([
            'comment_author'       => 'tester',
            'comment_author_email' => 'tester@writesdown.dev',
            'comment_content'      => 'New comment'
        ]);
        $I->expect('new comment saved');
        $I->dontSee('Name cannot be blank.', '.help-block');
        $I->dontSee('Email cannot be blank.', '.help-block');
        $I->dontSee('Content cannot be blank.', '.help-block');

        MediaComment::deleteAll(['comment_author'=>'tester']);
        Media::findOne(1)->updateAttributes(['media_comment_count' => '1']);
    }
}
