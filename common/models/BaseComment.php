<?php
/**
 * @file    BaseComment.php.
 * @date    6/4/2015
 * @time    3:53 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
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
 * @property string  $comment_author
 * @property string  $comment_author_email
 * @property string  $comment_author_url
 * @property string  $comment_author_ip
 * @property string  $comment_date
 * @property string  $comment_content
 * @property string  $comment_approved
 * @property string  $comment_agent
 * @property integer $comment_parent
 * @property integer $comment_user_id
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
abstract class BaseComment extends ActiveRecord
{
    /**
     * Constant of approved comment
     */
    const COMMENT_APPROVED = "approved";
    /**
     * Constant of unapproved comment
     */
    const COMMENT_UNAPPROVED = "unapproved";
    /**
     * Constant of trash
     */
    const COMMENT_TRASH = "trash";

    /**
     * @var
     */
    public $child;

    /**
     * @return array
     */
    public function scenarios(){
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
            [['comment_author', 'comment_author_email'], 'required', 'when' => function () {
                return Option::get('require_name_email') && Yii::$app->user->isGuest ? true : false;
            }],
            ['comment_author_email', 'filter', 'filter' => 'trim'],
            ['comment_author_email', 'email'],
            [['comment_content'], 'required'],
            ['comment_approved', 'default', 'value' => self::COMMENT_UNAPPROVED],
            ['comment_approved', 'in', 'range' => [self::COMMENT_APPROVED, self::COMMENT_UNAPPROVED, self::COMMENT_TRASH]],
            [['comment_parent', 'comment_user_id'], 'integer'],
            ['comment_parent', 'default', 'value' => 0],
            [['comment_author', 'comment_content'], 'string'],
            [['comment_date'], 'safe'],
            [['comment_author_email', 'comment_author_ip'], 'string', 'max' => 100],
            [['comment_author_url', 'comment_agent'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('writesdown', 'ID'),
            'comment_author'       => Yii::t('writesdown', 'Name'),
            'comment_author_email' => Yii::t('writesdown', 'Email'),
            'comment_author_url'   => Yii::t('writesdown', 'URL'),
            'comment_author_ip'    => Yii::t('writesdown', 'IP'),
            'comment_date'         => Yii::t('writesdown', 'Date'),
            'comment_content'      => Yii::t('writesdown', 'Content'),
            'comment_approved'     => Yii::t('writesdown', 'Comment Approved'),
            'comment_agent'        => Yii::t('writesdown', 'Agent'),
            'comment_parent'       => Yii::t('writesdown', 'Parent'),
            'comment_user_id'      => Yii::t('writesdown', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'comment_post_id']);
    }

    /**
     * Get comment approved in array
     *
     * @return array
     */
    public function getCommentApproved()
    {
        return [
            self::COMMENT_APPROVED   => 'Approved',
            self::COMMENT_UNAPPROVED => 'Unapproved',
            self::COMMENT_TRASH      => 'Trash'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert){

        if(parent::beforeSave($insert)){
            if( $this->isNewRecord ){
                if(!Yii::$app->user->isGuest) {
                    $this->comment_user_id      = Yii::$app->user->id;
                    $this->comment_author_email = Yii::$app->user->identity->email;
                    $this->comment_author       = Yii::$app->user->identity->display_name;
                }
                $this->comment_agent        = $_SERVER['HTTP_USER_AGENT'];
                $this->comment_author_ip    = $_SERVER['REMOTE_ADDR'];
                $this->comment_date         = date('Y-m-d H:i:s');
                $this->comment_approved     = self::COMMENT_APPROVED;
                if (Option::get('comment_moderation') && Yii::$app->user->isGuest) {
                    if (Option::get('comment_whitelist') && Option::get('require_name_email')) {
                        $hasComment = static::find()
                            ->andWhere(['comment_author_email' => $this->comment_author_email])
                            ->andWhere(['comment_approved' => self::COMMENT_APPROVED])
                            ->count();
                        if (!$hasComment) {
                            $this->comment_approved = self::COMMENT_UNAPPROVED;
                        }
                    } else {
                        $this->comment_approved = self::COMMENT_UNAPPROVED;
                    }
                }
            }
            return true;
        }
        return false;
    }
}