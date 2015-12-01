<?php
use tests\codeception\frontend\FunctionalTester;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('WritesDown');
$I->seeLink('WritesDown');
// $I->see('My Company');
// $I->seeLink('About');
// $I->click('About');
// $I->see('This is the About page.');
