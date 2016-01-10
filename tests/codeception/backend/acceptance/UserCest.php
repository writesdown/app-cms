<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\acceptance;

use common\models\User;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\_pages\_user\CreatePage;
use tests\codeception\backend\_pages\_user\IndexPage;
use tests\codeception\backend\_pages\_user\ProfilePage;
use tests\codeception\backend\_pages\_user\ResetPasswordPage;
use tests\codeception\backend\_pages\_user\UpdatePage;
use tests\codeception\backend\AcceptanceTester;
use yii\helpers\Url;

/**
 * Class UserCest
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class UserCest
{
    /**
     * This method is called before each cest class test method
     *
     * @param AcceptanceTester $I
     */
    public function _before($I)
    {
        $loginPage = LoginPage::openBy($I);
        $loginPage->submit(['username' => 'administrator', 'password' => 'administrator']);
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
        $I->wantTo('ensure that index user works');

        $indexPage = IndexPage::openBy($I);
        $I->see('Users', 'h1');

        $I->amGoingTo('submit search form with non existing user');
        $indexPage->submit(['username' => 'non_existing_user']);
        $I->expectTo('not see a record');
        $I->see('No results found.', '#user-grid-view');

        $I->amGoingTo('submit search form with existing user');
        $indexPage->submit(['username' => 'subscriber']);
        $I->expectTo('see user of which the username is subscriber');
        $I->see('subscriber', '#user-grid-view');
        $I->dontSee('author', '#user-grid-view');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testView(AcceptanceTester $I)
    {
        $I->wantTo('ensure that view user works');
        $I->amOnPage(Url::to(['/user/view', 'id' => 1]));
        $I->see('View User: superadmin', 'h1');
        $I->dontSeeLink('Update');
        $I->dontSeeLink('Delete');

        $I->amOnPage(Url::to(['/user/view', 'id' => 5]));
        $I->see('View User: contributor', 'h1');
        $I->seeLink('Update');
        $I->seeLink('Delete');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testCreate(AcceptanceTester $I)
    {
        $I->wantTo('ensure that create user works');
        $createPage = CreatePage::openBy($I);
        $I->see('Add New User', 'h1');
        $I->cantSeeElement('#user-role option[value="superadmin"]');

        $I->amGoingTo('submit user form with no data');
        $createPage->submit([]);
        $I->expectTo('see validation errors');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');

        $I->amGoingTo('submit user form with no correct email and password');
        $createPage->submit([
            'username' => 'newuser',
            'password' => 'newu',
            'email'    => 'newuser@email',
        ]);
        $I->expectTo('see that given email is not correct');
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->see('Password should contain at least 6 characters.', '.help-block');
        $I->see('Email is not a valid email address.', '.help-block');

        $I->amGoingTo('submit user form with correct data');
        $createPage->submit([
            'username' => 'newuser',
            'password' => 'newuser',
            'email'    => 'newuser@writesdown.dev',
        ]);
        $I->expect('new user saved');
        $I->see('View User: newuser', 'h1');

        User::deleteAll(['username' => 'newuser']);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testUpdate(AcceptanceTester $I)
    {
        $I->wantTo('ensure that update user works');
        $updatePage = UpdatePage::openBy($I);
        $I->see('Update User: editor', 'h1');
        $I->dontSeeElement('#user-password');

        $I->amGoingTo('submit update user form');
        $updatePage->submit([
            'full_name'    => 'Demoted Into Subscriber',
            'display_name' => 'Subscriber',
        ]);
        $I->expect('the user updated');
        $I->see('Demoted Into Subscriber');
        $I->see('Subscriber');

        User::findOne(3)->updateAttributes([
            'display_name'         => 'Editor',
            'full_name'            => 'Editor at WritesDown'
        ]);

        \Yii::$app->authManager->revokeAll(3);
        \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('editor'), 3);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testDelete(AcceptanceTester $I)
    {
        $I->wantTo('ensure that delete user works');
        IndexPage::openBy($I);
        $I->see('Users', 'h1');
        $I->dontSeeElement('#user-grid-view a[href="' . Url::to(['/user/delete', 'id' => 1]) . '"]');
        $I->seeElement('#user-grid-view a[href="' . Url::to(['/user/delete', 'id' => 6]) . '"]');
        if(method_exists($I, 'acceptPopup') && method_exists($I, 'wait')){
            $I->click('#user-grid-view a[href="' . Url::to(['/user/delete', 'id' => 6]) . '"]');
            $I->acceptPopup();
            $I->wait(5);
            $I->dontSee('subscriber', '#user-grid-view');
        }
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testProfile(AcceptanceTester $I)
    {
        $I->wantTo('ensure that update profile works');
        $profilePage = ProfilePage::openBy($I);
        $I->see('My Profile', 'h1');

        $I->amGoingTo('submit update-profile form with incorrect email');
        $profilePage->submit([
            'email' => 'test@test@test',
        ]);
        $I->expectTo('see that email is not corect');
        $I->see('Email is not a valid email address.', '.help-block');

        $I->amGoingTo('submit update profile form with new correct email');
        $profilePage->submit([
            'email' => 'tester@test.test',
        ]);
        $I->expect('new email saved');
        $I->see('View User: administrator', 'h1');
        $I->see('tester@test.test');

        User::findOne(2)->updateAttributes([
            'email' => 'administrator@writesdwon.dev'
        ]);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testResetPassword(AcceptanceTester $I)
    {
        $I->wantTo('ensure that reset password works');
        $resetPassword = ResetPasswordPage::openBy($I);
        $I->see('Reset Password', 'h1');

        $I->amGoingTo('submit reset password form with empty data');
        $resetPassword->submit([]);
        $I->expectTo('see validation errors');
        $I->see('Old Password cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');
        $I->see('Repeat Password cannot be blank.', '.help-block');

        $I->amGoingTo('submit reset password form with no correct old password');
        $resetPassword->submit([
            'password_old' => 'wrong_password',
            'password' => 'my.new.password',
            'password_repeat' => 'my.new.password',
        ]);
        $I->expectTo('see validations error');
        $I->see('The old password is not correct.', '.help-block');

        $I->amGoingTo('submit reset password form with correct data');
        $resetPassword->submit([
            'password_old' => 'administrator',
            'password' => 'my.new.password',
            'password_repeat' => 'my.new.password',
        ]);
        $I->expect('new password saved');
        $I->see('View User: administrator');

        User::findOne(2)->updateAttributes([
            'auth_key'             => '0fQDfzYWWt_W4tHLv34YTEjP1Pk5zzRe',
            'password_hash'        => '$2y$13$lf03M5DAWI7qwJ3UWKq6ruAYdxRZj9RnNWqRhORY1xuFCTvbFFWv.',
        ]);
    }
}
