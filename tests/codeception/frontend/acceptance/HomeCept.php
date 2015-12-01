<?php
use tests\codeception\frontend\AcceptanceTester;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('WritesDown');
$I->seeLink('WritesDown');
// $I->see('My Company');
// $I->seeLink('About');
// $I->click('About');
// $I->see('This is the About page.');