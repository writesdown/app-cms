<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\acceptance;

use common\models\Option;
use common\models\User;
use tests\codeception\backend\_pages\_site\LoginPage;
use tests\codeception\backend\_pages\_site\RequestPasswordResetPage;
use tests\codeception\backend\_pages\_site\ResetPasswordPage;
use tests\codeception\backend\_pages\_site\SignupPage;
use tests\codeception\backend\AcceptanceTester;

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
        $I->wantTo('ensure that home, login & logout works');

        // LOGIN
        $loginPage = LoginPage::openBy($I);
        $I->see('Sign in to start your session', 'p');

        $I->amGoingTo('submit login form with no data');
        $loginPage->submit([]);
        $I->expectTo('see error messages');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');

        $I->amGoingTo('submit login form with wrong password');
        $loginPage->submit([
            'username' => 'superadmin',
            'password' => '1\' OR \'1\'=1\''
        ]);
        $I->expectTo('see error messages');
        $I->see('Incorrect username or password.', '.help-block');

        $I->amGoingTo('submit login form with correct data');
        $loginPage->submit([
            'username' => 'subscriber',
            'password' => 'subscriber'
        ]);
        $I->expectTo('user successfully login to admin page');

        // INDEX
        $I->see('Dashboard', 'h1');
        $I->seeLink('Home');

        // LOGOUT
        $I->seeLink('subscriber');
        $I->click('subscriber');
        $I->seeLink('Sign Out');
        $I->click('Sign Out');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testSignup(AcceptanceTester $I){
        Option::up('allow_signup', '1');
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->see('Register a new membership', 'p');

        $I->amGoingTo('submit signup form with no data');
        $signupPage->submit([]);
        $I->expectTo('see validations error');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');

        $I->amGoingTo('submit signup form with no correct email and password');
        $signupPage->submit([
            'username' => 'newuser',
            'email'    => 'newuser.email',
            'password' => 'pass'
        ]);
        $I->expectTo('see that email and password are not correct.');
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->see('Email is not a valid email address.', '.help-block');
        $I->see('Password should contain at least 6 characters.', '.help-block');

        $I->amGoingTo('submit signup form with correct data');
        $signupPage->submit([
            'username' => 'newuser',
            'email'    => 'newuser@writesdown.dev',
            'password' => 'password'
        ]);
        $I->expect('new user saved.');
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->dontSee('Email is not a valid email address.', '.help-block');
        $I->dontSee('Password should contain at least 6 characters.', '.help-block');

        User::deleteAll(['username' => 'newuser']);
        Option::up('allow_signup', '0');
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testRequestPasswordReset(AcceptanceTester $I){
        $I->wantTo('ensure that request password reset works');

        $requestPasswordResetPage = RequestPasswordResetPage::openBy($I);
        $I->see('Please fill out your email. A link to reset password will be sent there.', 'p');

        $I->amGoingTo('submit request password token form with no data');
        $requestPasswordResetPage->submit([]);
        $I->expectTo('see validation error');
        $I->see('Email cannot be blank.', '.help-block');

        $I->amGoingTo('submit request password form with no correct email');
        $requestPasswordResetPage->submit(['email' => 'tester.email']);
        $I->expectTo('see that email is not correct');
        $I->see('Email is not a valid email address.', '.help-block');

        $I->amGoingTo('submit request password form with no correct user');
        $requestPasswordResetPage->submit(['email' => 'wrong_user@writesdown.dev']);
        $I->expectTo('see that user is not correct');
        $I->see('There is no user with such email.', '.help-block');

        $I->amGoingTo('submit request password form with correct user');
        $requestPasswordResetPage->submit(['email' => 'subscriber@writesdown.dev']);
        $I->expect('email sent');
        $I->see('Check your email for further instructions.');

        User::findOne(6)->updateAttributes([
            'password_reset_token' => '-uFQrRFZWRaP4B06w9z7GPXyVjyrgDFm_' . time(),
        ]);
    }

    /**
     * @param AcceptanceTester $I
     */
    public function testResetPassword(AcceptanceTester $I){
        $I->wantTo('ensure that reset password works');
        $resetPasswordPage = ResetPasswordPage::openBy($I);
        $I->see('Please choose your new password:', 'p');

        $I->amGoingTo('submit password form with no data');
        $resetPasswordPage->submit([]);
        $I->expectTo('see validation error');
        $I->see('Password cannot be blank', '.help-block');

        $I->amGoingTo('submit password form with no correct data');
        $resetPasswordPage->submit(['password' => '1234x']);
        $I->expectTo('see that password is not valid');
        $I->see('Password should contain at least 6 characters.', '.help-block');

        $I->amGoingTo('submit password form with valid data');
        $resetPasswordPage->submit(['password' => 'my.new.password']);
        $I->expect('new password saved');
        $I->see('New password was saved.');

        User::findOne(6)->updateAttributes([
            'auth_key'             => '89jQUg-3NpEgvmXgzs1OWXoILe4sJwi1',
            'password_hash'        => '$2y$13$NdYAATL.ACEqOmAX6cL1B.ZBZ2pUstB.xMjqFcLrR1ldbHG9yXgwa',
        ]);
    }
}
