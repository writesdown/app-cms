<?php
/**
 * @file      Media.php.
 * @date      6/4/2015
 * @time      4:44 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;
use yii\web\UploadedFile;
use common\components\Json;

/**
 * This is the model class for table "{{%media}}".
 *
 * @property integer        $id
 * @property integer        $media_author
 * @property integer        $media_post_id
 * @property string         $media_title
 * @property string         $media_excerpt
 * @property string         $media_content
 * @property string         $media_password
 * @property string         $media_date
 * @property string         $media_modified
 * @property string         $media_slug
 * @property string         $media_mime_type
 * @property string         $media_comment_status
 * @property integer        $media_comment_count
 * @property string         $url
 * @property UploadedFile   $file
 * @property string         $uploadUrl
 *
 * @property Post           $mediaPost
 * @property User           $mediaAuthor
 * @property MediaComment[] $mediaComments
 * @property MediaMeta[]    $mediaMeta
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class Media extends ActiveRecord
{
    public $username;
    public $post_title;
    public $file;

    const COMMENT_STATUS_OPEN = 'open';
    const COMMENT_STATUS_CLOSE = 'close';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%media}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'      => SluggableBehavior::className(),
                'attribute'  => 'media_title',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['media_slug'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['upload'] = ['file'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_title', 'media_mime_type'], 'required'],
            [['media_author', 'media_post_id', 'media_comment_count'], 'integer'],
            [['media_title', 'media_excerpt', 'media_content'], 'string'],
            [['media_password', 'media_slug'], 'string', 'max' => 255],
            [['media_mime_type'], 'string', 'max' => 100],
            [['media_comment_status'], 'string', 'max' => 20],
            [['media_date', 'media_modified', 'media_slug'], 'safe'],
            [['file'], 'file', 'maxSize' => 1024 * 1024 * 25],
            [['file'], 'required', 'on' => 'upload'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('writesdown', 'ID'),
            'media_author'         => Yii::t('writesdown', 'Author'),
            'media_post_id'        => Yii::t('writesdown', 'Attached to'),
            'media_title'          => Yii::t('writesdown', 'Title'),
            'media_excerpt'        => Yii::t('writesdown', 'Caption'),
            'media_content'        => Yii::t('writesdown', 'Description'),
            'media_password'       => Yii::t('writesdown', 'Password'),
            'media_date'           => Yii::t('writesdown', 'Uploaded'),
            'media_modified'       => Yii::t('writesdown', 'Updated'),
            'media_slug'           => Yii::t('writesdown', 'Slug'),
            'media_mime_type'      => Yii::t('writesdown', 'Mime Type'),
            'media_comment_status' => Yii::t('writesdown', 'Comment Status'),
            'media_comment_count'  => Yii::t('writesdown', 'Comment Count'),
            'username'             => Yii::t('writesdown', 'Author'),
            'post_title'           => Yii::t('writesdown', 'Post Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'media_post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'media_author']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaComments()
    {
        return $this->hasMany(MediaComment::className(), ['comment_media_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaMeta()
    {
        return $this->hasMany(MediaMeta::className(), ['media_id' => 'id']);
    }

    /**
     * Get comment status as array
     */
    public function getCommentStatus()
    {
        return [
            self::COMMENT_STATUS_OPEN  => "Open",
            self::COMMENT_STATUS_CLOSE => "Close",
        ];
    }

    /**
     * Get meta for current media.
     *
     * @param $meta_name
     *
     * @return boolean|array|string
     */
    public function getMeta($meta_name)
    {
        /* @var $model \common\models\MediaMeta */
        $model = MediaMeta::find()->andWhere(['meta_name' => $meta_name])->andWhere(['media_id' => $this->id])->one();

        if ($model) {

            if (Json::isJson($model->meta_value)) {
                return Json::decode($model->meta_value);
            }

            return $model->meta_value;
        }

        return false;
    }

    /**
     * Add new meta data for current media.
     *
     * @param $meta_name
     * @param $meta_value
     *
     * @return bool
     */
    public function setMeta($meta_name, $meta_value)
    {
        if (is_array($meta_value) || is_object($meta_value)) {
            $meta_value = Json::encode($meta_value);
        }

        $model = new MediaMeta();
        $model->media_id = $this->id;
        $model->meta_name = $meta_name;
        $model->meta_value = $meta_value;

        return $model->save();
    }

    /**
     * Update meta data for current media.
     *
     * @param $meta_name
     * @param $meta_value
     *
     * @return bool
     */
    public function upMeta($meta_name, $meta_value)
    {
        /* @var $model \common\models\MediaMeta */
        $model = MediaMeta::find()->andWhere(['meta_name' => $meta_name])->andWhere(['media_id' => $this->id])->one();

        if (is_array($meta_value) || is_object($meta_value))
            $meta_value = Json::encode($meta_value);

        $model->meta_value = $meta_value;

        return $model->save();
    }

    /**
     * Get permalink of current media
     *
     * @return string
     */
    public function getUrl()
    {
        return Yii::$app->urlManagerFront->createAbsoluteUrl(['/media/view', 'id' => $this->id]);
    }

    /**
     * Get upload URL
     *
     * @return string
     */
    public function getUploadUrl()
    {
        return Yii::$app->urlManagerFront->hostInfo . Yii::$app->urlManagerFront->baseUrl . '/uploads/';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->media_author = Yii::$app->user->id;
                $this->media_date = date('Y-m-d H:i:s');
                $this->media_comment_status = self::COMMENT_STATUS_OPEN;
                $this->media_comment_count = 0;
            }
            $this->media_modified = date('Y-m-d H:i:s');

            return true;
        }

        return false;
    }
}