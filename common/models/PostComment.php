<?php
/**
 * @file      PostComment.php.
 * @date      6/4/2015
 * @time      4:34 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%post_comment}}".
 *
 * @property integer $comment_post_id
 *
 * @property Post    $commentPost
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class PostComment extends BaseComment
{
    /**
     * @var string
     */
    public $post_title;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['comment_post_id', 'required'],
            ['comment_post_id', 'integer'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'comment_post_id' => Yii::t('writesdown', 'Comment to'),
            'post_title'      => Yii::t('writesdown', 'Post Title')
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'comment_post_id']);
    }
}