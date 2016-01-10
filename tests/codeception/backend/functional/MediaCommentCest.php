<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\functional;

use common\models\MediaComment;
use tests\codeception\backend\_pages\_mediacomment\IndexPage;
use tests\codeception\backend\_pages\_mediacomment\ReplyPage;
use tests\codeception\backend\_pages\_mediacomment\UpdatePage;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\FunctionalTester;
use tests\codeception\common\fixtures\MediaCommentFixture;
use tests\codeception\common\fixtures\MediaFixture;
use tests\codeception\common\fixtures\MediaMetaFixture;
use yii\helpers\Url;

/**
 * Class MediaCommentCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class MediaCommentCest
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
        $I->wantTo('ensure that index media-comment works');
        $indexPage = IndexPage::openBy($I);
        $I->see('Media Comments', 'h1');

        $I->amGoingTo('submit search form with non existing media-comment');
        $indexPage->submit(['comment_author' => 'non_existing_media_comment']);
        $I->expectTo('not see a record');
        $I->see('No results found.', '#media-comment-grid-view');

        $I->amGoingTo('submit search form with existing media');
        $indexPage->submit(['comment_author' => 'Mr']);
        $I->expectTo('see media-comments of which author contains Mr');
        $I->see('Mr. WritesDown', '#media-comment-grid-view');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testUpdate(FunctionalTester $I)
    {
        $I->wantTo('ensure that update media-comment works');
        $updatePage = UpdatePage::openBy($I);
        $I->see('Update Media Comment: 1', 'h1');

        $I->amGoingTo('submit media-comment with no correct email & url');
        $updatePage->submit([
            'comment_author'       => 'Tester',
            'comment_author_email' => 'tester.author@test',
            'comment_author_url'   => 'http://.com'
        ]);
        $I->expectTo('see that email & url not correct');
        $I->see('Email is not a valid email address.', '.help-block');
        $I->see('URL is not a valid URL.', '.help-block');

        $I->amGoingTo('submit media-comment with correct data');
        $updatePage->submit([
            'comment_author'       => 'Tester',
            'comment_author_email' => 'tester@tester.com',
            'comment_author_url'   => 'http://tester.com'
        ]);
        $I->expect('media-comment updated');
        $I->dontSee('Email is not a valid email address.', '.help-block');
        $I->dontSee('URL is not a valid URL.', '.help-block');

        MediaComment::findOne(1)->updateAll([
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
        $I->wantTo('ensure that delete media-comment works');
        IndexPage::openBy($I);
        $I->see('Media Comments');

        $I->seeElement('a[href="' . Url::to(['/media-comment/delete', 'id' => 1]) . '"]');

        // Delete only work for acceptance JS
    }

    /**
     * @param FunctionalTester $I
     */
    public function testReply(FunctionalTester $I)
    {
        $I->wantTo('ensure that reply media-comment works');
        $replyPage = ReplyPage::openBy($I);

        $I->amGoingTo('reply media-comment with no content');
        $replyPage->submit('');
        $I->expectTo('see validation error');
        $I->see('Content cannot be blank.', '.help-block');

        // Reply only work for acceptance $_POST['REMOTE_ADDR']
    }

    /**
     * Load default fixture for media
     */
    protected function loadFixtures()
    {
        $mediaFixture = new MediaFixture();
        $mediaFixture->load();

        $mediaMetaFixture = new MediaMetaFixture();
        $mediaMetaFixture->load();

        $mediaCommentFixture = new MediaCommentFixture();
        $mediaCommentFixture->load();
    }
}
