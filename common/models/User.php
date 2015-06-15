<?php
/**
 * @file      User.php.
 * @date      6/4/2015
 * @time      4:17 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string  $username
 * @property string  $email
 * @property string  $full_name
 * @property string  $display_name
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $auth_key
 * @property integer $status
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $login_at
 * @property string  $role
 * @property string  $password
 * @property string  $password_old
 * @property string  $password_repeat
 * @property string  $url
 *
 * @property Media[] $media
 * @property Post[]  $posts
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 5;
    const STATUS_ACTIVE = 10;

    public $password;
    public $password_old;
    public $password_repeat;

    public $role;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'full_name', 'display_name', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['username', 'email'], 'required'],
            [['username', 'email'], 'unique'],
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            [['status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['auth_key'], 'string', 'max' => 32],
            [['created_at', 'updated_at', 'login_at', 'role'], 'safe'],
            [['password', 'password_old', 'password_repeat'], 'required', 'on' => 'resetPassword'],
            ['password', 'required', 'on' => 'register'],
            ['password', 'string', 'min' => 6],
            ['password_old', 'passwordValidation'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('writesdown', 'ID'),
            'username'             => Yii::t('writesdown', 'Username'),
            'email'                => Yii::t('writesdown', 'Email'),
            'full_name'            => Yii::t('writesdown', 'Full Name'),
            'display_name'         => Yii::t('writesdown', 'Display Name'),
            'password_hash'        => Yii::t('writesdown', 'Password Hash'),
            'password_reset_token' => Yii::t('writesdown', 'Password Reset Token'),
            'auth_key'             => Yii::t('writesdown', 'Auth Key'),
            'status'               => Yii::t('writesdown', 'Status'),
            'created_at'           => Yii::t('writesdown', 'Registered'),
            'updated_at'           => Yii::t('writesdown', 'Updated'),
            'login_at'             => Yii::t('writesdown', 'Last Activity'),
            'role'                 => Yii::t('writesdown', 'Role'),
            'password'             => Yii::t('writesdown', 'Password'),
            'password_repeat'      => Yii::t('writesdown', 'Repeat Password'),
            'password_old'         => Yii::t('writesdown', 'Old Password'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['media_author' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['post_author' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);

        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Validate password when resetting
     */
    public function passwordValidation()
    {
        $user = self::findOne(Yii::$app->user->id);
        if (!$user || !$user->validatePassword($this->password_old)) {
            $this->addError('password_old', Yii::t('writesdown', 'The old password is not correct.'));
        }
    }

    /**
     * Get array of user status.
     *
     * @return array
     */
    public function getStatus()
    {
        return [
            self::STATUS_ACTIVE   => "Activated",
            self::STATUS_INACTIVE => "Unactivated",
            self::STATUS_DELETED  => "Removed",
        ];
    }

    /**
     * Get text from user status.
     *
     * @return string
     */
    public function getStatusText()
    {
        $status = $this->getStatus();

        return isset($status[ $this->status ]) ? $status[ $this->status ] : "unknown($this->status)";
    }

    /**
     * Get user's frontend url
     *
     * @return string
     */
    public function getUrl()
    {
        return Yii::$app->urlManagerFront->createAbsoluteUrl(['user/view', 'id' => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = new Expression('NOW()');
                if (!$this->status) {
                    $this->status = self::STATUS_INACTIVE;
                }
                if (!$this->display_name) {
                    $this->display_name = $this->username;
                }
            }
            $this->updated_at = new Expression('NOW()');

            return true;
        }

        return false;
    }
}