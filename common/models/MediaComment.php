<?php
/**
 * @file      MediaComment.php.
 * @date      6/4/2015
 * @time      4:41 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%media_comment}}".
 *
 * @property integer $comment_media_id
 *
 * @property Media   $commentMedia
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MediaComment extends BaseComment
{
    /**
     * @var string
     */
    public $media_title;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['comment_media_id', 'required'],
            ['comment_media_id', 'integer']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'comment_media_id' => Yii::t('writesdown', 'Comment to'),
            'media_title'      => Yii::t('writesdown', 'Media Title'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'comment_media_id']);
    }
}
