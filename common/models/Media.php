<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models;

use common\components\Json;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%media}}".
 *
 * @property integer $id
 * @property integer $author
 * @property integer $post_id
 * @property string $title
 * @property string $excerpt
 * @property string $content
 * @property string $password
 * @property string $date
 * @property string $modified
 * @property string $slug
 * @property string $mime_type
 * @property string $comment_status
 * @property integer $comment_count
 *
 * @property string $url
 * @property string $uploadUrl
 *
 * @property Post $mediaPost
 * @property User $mediaAuthor
 * @property MediaComment[] $mediaComments
 * @property MediaMeta[] $mediaMeta
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class Media extends ActiveRecord
{
    const COMMENT_STATUS_OPEN = 'open';
    const COMMENT_STATUS_CLOSE = 'close';

    public $username;
    public $post_title;
    /**
     * @var \yii\web\UploadedFile
     */
    public $file;

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
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['slug']],
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
            [['title', 'mime_type'], 'required'],
            [['author', 'post_id', 'comment_count'], 'integer'],
            [['title', 'excerpt', 'content'], 'string'],
            [['password', 'slug'], 'string', 'max' => 255],
            ['mime_type', 'string', 'max' => 100],
            ['comment_status', 'string', 'max' => 20],
            [['date', 'modified', 'slug'], 'safe'],
            [
                'file',
                'file',
                'maxSize' => 1024 * 1024 * 25,
                'extensions' => 'jpg, jpeg, png, gif,'
                    . 'pdf, doc, docx, key, ppt, pptx, pps, ppsx, odt, xls, xlsx, zip,'
                    . 'mp3, m4a, ogg, wav,'
                    . 'mp4, m4v, mov, wmv, avi, mpg,ogv, 3gp, 3g2',
            ],
            ['file', 'required', 'on' => 'upload'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('writesdown', 'ID'),
            'author' => Yii::t('writesdown', 'Author'),
            'post_id' => Yii::t('writesdown', 'Attached to'),
            'title' => Yii::t('writesdown', 'Title'),
            'excerpt' => Yii::t('writesdown', 'Caption'),
            'content' => Yii::t('writesdown', 'Description'),
            'password' => Yii::t('writesdown', 'Password'),
            'date' => Yii::t('writesdown', 'Uploaded'),
            'modified' => Yii::t('writesdown', 'Updated'),
            'slug' => Yii::t('writesdown', 'Slug'),
            'mime_type' => Yii::t('writesdown', 'Mime Type'),
            'comment_status' => Yii::t('writesdown', 'Comment Status'),
            'comment_count' => Yii::t('writesdown', 'Comment Count'),
            'username' => Yii::t('writesdown', 'Author'),
            'post_title' => Yii::t('writesdown', 'Post Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediaComments()
    {
        return $this->hasMany(MediaComment::className(), ['media_id' => 'id']);
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
    public function getCommentStatuses()
    {
        return [
            self::COMMENT_STATUS_OPEN => Yii::t('writesdown', 'Open'),
            self::COMMENT_STATUS_CLOSE => Yii::t('writesdown', 'Close'),
        ];
    }

    /**
     * Get meta for current media.
     *
     * @param string $name
     * @return mixed|null|string
     */
    public function getMeta($name)
    {
        /* @var $model \common\models\MediaMeta */
        $model = MediaMeta::findOne(['name' => $name, 'media_id' => $this->id]);

        if ($model) {
            if (Json::isJson($model->value)) {
                return Json::decode($model->value);
            }

            return $model->value;
        }

        return null;
    }

    /**
     * Add new meta data for current media.
     *
     * @param string $name
     * @param string|array $value
     * @return bool
     */
    public function setMeta($name, $value)
    {
        if (is_array($value) || is_object($value)) {
            $value = Json::encode($value);
        }

        if ($this->getMeta($name) !== null) {
            return $this->upMeta($name, $value);
        }

        $model = new MediaMeta([
            'media_id' => $this->id,
            'name' => $name,
            'value' => $value,
        ]);

        return $model->save();
    }

    /**
     * Update meta data for current media.
     *
     * @param string $name
     * @param string|array $value
     * @return bool
     */
    public function upMeta($name, $value)
    {
        /* @var $model \common\models\MediaMeta */
        $model = MediaMeta::findOne(['name' => $name, 'media_id' => $this->id]);

        if (is_array($value) || is_object($value)) {
            $value = Json::encode($value);
        }

        $model->value = $value;

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
     * Get upload URL.
     *
     * @return string
     */
    public static function getUploadUrl()
    {
        return Yii::$app->urlManagerFront->hostInfo . Yii::$app->urlManagerFront->baseUrl . '/uploads/';
    }

    /**
     * Get media image thumbnail. If the version is not found, full size will be returned.
     *
     * @param string $version Version of image thumbnail.
     * @param array $options Html::image options.
     * @return string
     */
    public function getThumbnail($version = 'thumbnail', $options = [])
    {
        $thumbnail = '';
        $metadata = $this->getMeta('metadata');

        if (preg_match("/^image/", $this->mime_type)) {
            if (isset($metadata['versions'][$version])) {
                $imageSrc = $metadata['versions'][$version]['url'];
                $imageWidth = $metadata['versions'][$version]['width'];
                $imageHeight = $metadata['versions'][$version]['height'];
            } else {
                $imageSrc = $metadata['versions']['full']['url'];
                $imageWidth = $metadata['versions']['full']['width'];
                $imageHeight = $metadata['versions']['full']['height'];
            }

            $thumbnail = Html::img($this->getUploadUrl() . $imageSrc, ArrayHelper::merge([
                'width' => $imageWidth,
                'height' => $imageHeight,
                'alt' => $this->title,
            ], $options));
        }

        return $thumbnail;
    }

    /**
     * Get permission to access model by current user.
     *
     * @return bool
     */
    public function getPermission()
    {
        if (!Yii::$app->user->can('editor') && $this->author !== Yii::$app->user->id) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->author = Yii::$app->user->id;
                $this->date = date('Y-m-d H:i:s');
                $this->comment_status = self::COMMENT_STATUS_OPEN;
                $this->comment_count = 0;
            }
            $this->modified = date('Y-m-d H:i:s');

            return true;
        }

        return false;
    }
}
