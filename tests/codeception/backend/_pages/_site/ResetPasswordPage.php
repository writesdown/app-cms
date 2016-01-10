<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\backend\_pages\_site;

use common\models\User;
use yii\codeception\BasePage;

/**
 * Class ResetPasswordPage
 *
 * @property \tests\codeception\frontend\FunctionalTester | \tests\codeception\frontend\AcceptanceTester | \tests\codeception\backend\FunctionalTester | \tests\codeception\backend\AcceptanceTester $actor
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class ResetPasswordPage extends BasePage
{
    /**
     * @inheritdoc
     */
    public function __construct($I)
    {
        $this->route = ['/site/reset-password', 'token' => User::findOne('6')->password_reset_token];
        parent::__construct($I);
    }

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        foreach ($data as $field => $value) {
            $this->actor->fillField('input[name="ResetPasswordForm[' . $field . ']"]', $value);
        }

        $this->actor->click('Save', '#reset-password-form');

        // Wait
        if (method_exists($this->actor, 'wait')) {
            $this->actor->wait(3);
        }
    }
}
