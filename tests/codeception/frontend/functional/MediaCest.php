<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\frontend\functional;

use common\models\Media;
use tests\codeception\common\fixtures\MediaCommentFixture;
use tests\codeception\common\fixtures\MediaFixture;
use tests\codeception\common\fixtures\MediaMetaFixture;
use tests\codeception\frontend\_pages\MediaViewPage;
use tests\codeception\frontend\FunctionalTester;
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
     * @param FunctionalTester $I
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
    public function testView(FunctionalTester $I)
    {
        $I->wantTo('ensure that view media works');

        $I->amOnPage(Url::to(['/media/view', 'id' => 1]));
        $I->see('Test Media', 'h1');
        $I->seeLink('Test Media');

        $I->amOnPage(Url::to(['/media/view', 'mediaslug' => 'test-media']));
        $I->see('Test Media', 'h1');
        $I->seeLink('Test Media');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testProtected(FunctionalTester $I)
    {
        Media::findOne(1)->updateAttributes(['media_password' => 'mediapassword']);

        $I->wantTo('ensure that protected media works');

        $mediaView = MediaViewPage::openBy($I);
        $I->see('Test Media', 'h1');

        $I->amGoingTo('submit password form with incorrect password');
        $mediaView->submitPassword('wrong_password');
        $I->expectTo('not see the media');
        $I->dontSeeElement('.entry-meta');

        $I->amGoingTo('submit password form with correct password');
        $mediaView->submitPassword('mediapassword');
        $I->expectTo('see the post');
        $I->seeElement('.entry-meta');
        $I->seeLink('Test Media');

        Media::findOne(1)->updateAttributes(['media_password' => '']);
    }
}
