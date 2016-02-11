<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%post_comment}}".
 *
 * @property integer $id
 * @property string $author
 * @property string $email
 * @property string $url
 * @property string $ip
 * @property string $date
 * @property string $content
 * @property string $status
 * @property string $agent
 * @property integer $parent
 * @property integer $user_id
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
abstract class BaseComment extends ActiveRecord
{
    const STATUS_APPROVED = "approved";
    const STATUS_NOT_APPROVED = "unapproved";
    const STATUS_TRASHED = "trashed";

    /**
     * @var BaseComment[]
     */
    public $child;

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['reply'] = $scenarios['default'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['author', 'email'],
                'required',
                'when' => function () {
                    return Option::get('require_name_email') && Yii::$app->user->isGuest ? true : false;
                },
            ],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['content', 'required'],
            ['status', 'default', 'value' => self::STATUS_NOT_APPROVED],
            [
                'status',
                'in',
                'range' => [self::STATUS_APPROVED, self::STATUS_NOT_APPROVED, self::STATUS_TRASHED],
            ],
            [['parent', 'user_id'], 'integer'],
            ['parent', 'default', 'value' => 0],
            [['author', 'content'], 'string'],
            ['date', 'safe'],
            [['email', 'ip'], 'string', 'max' => 100],
            ['agent', 'string', 'max' => 255],
            ['url', 'url'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('writesdown', 'ID'),
            'author' => Yii::t('writesdown', 'Name'),
            'email' => Yii::t('writesdown', 'Email'),
            'url' => Yii::t('writesdown', 'URL'),
            'ip' => Yii::t('writesdown', 'IP'),
            'date' => Yii::t('writesdown', 'Date'),
            'content' => Yii::t('writesdown', 'Content'),
            'status' => Yii::t('writesdown', 'Status'),
            'agent' => Yii::t('writesdown', 'Agent'),
            'parent' => Yii::t('writesdown', 'Parent'),
            'user_id' => Yii::t('writesdown', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * Get comment status in array
     *
     * @return array
     */
    public function getStatuses()
    {
        return [
            self::STATUS_APPROVED => Yii::t('writesdown', 'Approved'),
            self::STATUS_NOT_APPROVED => Yii::t('writesdown', 'Not Approved'),
            self::STATUS_TRASHED => Yii::t('writesdown', 'Trashed'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (!Yii::$app->user->isGuest) {
                    $this->user_id = Yii::$app->user->id;
                    $this->email = Yii::$app->user->identity->email;
                    $this->author = Yii::$app->user->identity->display_name;
                }
                $this->agent = $_SERVER['HTTP_USER_AGENT'];
                $this->ip = $_SERVER['REMOTE_ADDR'];
                $this->date = date('Y-m-d H:i:s');
                $this->status = self::STATUS_APPROVED;
                if (Option::get('comment_moderation') && Yii::$app->user->isGuest) {
                    if (Option::get('comment_whitelist') && Option::get('require_name_email')) {
                        $hasComment = static::find()
                            ->andWhere(['email' => $this->email])
                            ->andWhere(['status' => self::STATUS_APPROVED])
                            ->count();
                        if (!$hasComment) {
                            $this->status = self::STATUS_NOT_APPROVED;
                        }
                    } else {
                        $this->status = self::STATUS_NOT_APPROVED;
                    }
                }
            }

            return true;
        }

        return false;
    }
}
