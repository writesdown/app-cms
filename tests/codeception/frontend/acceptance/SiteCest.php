<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\frontend\acceptance;

use tests\codeception\common\fixtures\PostCommentFixture;
use tests\codeception\common\fixtures\PostFixture;
use tests\codeception\common\fixtures\PostTypeFixture;
use tests\codeception\common\fixtures\PostTypeTaxonomyFixture;
use tests\codeception\common\fixtures\TaxonomyFixture;
use tests\codeception\common\fixtures\TermFixture;
use tests\codeception\common\fixtures\TermRelationshipFixture;
use tests\codeception\frontend\_pages\ContactPage;
use tests\codeception\frontend\AcceptanceTester;

/**
 * Class SiteCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class SiteCest
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
    public function testIndex(AcceptanceTester $I){
        $I->wantTo('ensure that home works');
        $I->amOnPage(\Yii::$app->homeUrl);
        $I->seeLink('Sample Post');
        $I->seeLink('Sample Page');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testContact(AcceptanceTester $I){
        $I->wantTo('ensure that contact works');

        $contactPage = ContactPage::openBy($I);

        $I->see('Contact', 'h1');

        $I->amGoingTo('submit contact form with no data');
        $contactPage->submit([]);
        $I->expectTo('see validations errors');
        $I->see('Contact', 'h1');
        $I->see('Name cannot be blank', '.help-block');
        $I->see('Email cannot be blank', '.help-block');
        $I->see('Subject cannot be blank', '.help-block');
        $I->see('Body cannot be blank', '.help-block');
        $I->see('The verification code is incorrect', '.help-block');

        $I->amGoingTo('submit contact form with not correct email');
        $contactPage->submit([
            'name'       => 'tester',
            'email'      => 'tester.email',
            'subject'    => 'test subject',
            'body'       => 'test content',
            'verifyCode' => 'testme',
        ]);
        $I->expectTo('see that email address is wrong');
        $I->dontSee('Name cannot be blank', '.help-block');
        $I->see('Email is not a valid email address.', '.help-block');
        $I->dontSee('Subject cannot be blank', '.help-block');
        $I->dontSee('Body cannot be blank', '.help-block');
        $I->dontSee('The verification code is incorrect', '.help-block');

        $I->amGoingTo('submit contact form with correct data');
        $contactPage->submit([
            'name'       => 'tester',
            'email'      => 'tester@example.com',
            'subject'    => 'test subject',
            'body'       => 'test content',
            'verifyCode' => 'testme',
        ]);
        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testSearch(AcceptanceTester $I){
        $I->wantTo('ensure that search page works');

        $I->amOnPage(\Yii::$app->homeUrl);

        $I->submitForm('#sidebar .form-search', [
            's' => 'test12345qwerty'
        ]);
        if (method_exists($I, 'wait')) {
            $I->wait(3);
        }
        $I->see('Not Found (#404)', 'h1');

        $I->submitForm('#sidebar .form-search', [
            's' => 'sample post'
        ]);
        if (method_exists($I, 'wait')) {
            $I->wait(3);
        }
        $I->seeLink('Sample Post');
    }
}
