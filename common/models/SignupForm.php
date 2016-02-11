<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Class SignupForm
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class SignupForm extends Model
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
    /**
     * @var boolean
     */
    public $term_condition;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            [['username', 'email', 'password', 'term_condition'], 'required'],
            [
                'username',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('writesdown', 'This username has already been taken.'),
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('writesdown', 'This email address has already been taken.'),
            ],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole(Option::get('default_role')), $user->id);

                return $user;
            }
        }

        return null;
    }
}
