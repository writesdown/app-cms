<?php
/**
 * @file      Post.php.
 * @date      6/4/2015
 * @time      4:35 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\behaviors\SluggableBehavior;
use common\components\Json;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer            $id
 * @property integer            $post_author
 * @property integer            $post_type
 * @property string             $post_title
 * @property string             $post_excerpt
 * @property string             $post_content
 * @property string             $post_date
 * @property string             $post_modified
 * @property string             $post_status
 * @property string             $post_password
 * @property string             $post_slug
 * @property string             $post_comment_status
 * @property integer            $post_comment_count
 * @property string             $url
 * @property []                 $poststatus
 *
 * @property Media[]            $media
 * @property PostType           $postType
 * @property User               $postAuthor
 * @property PostComment[]      $postComments
 * @property PostMeta[]         $postMeta
 * @property TermRelationship[] $termRelationships
 * @property Term[]             $terms
 *
 * @package  common\models
 * @author   Agiel K. Saputra <13nightevil@gmail.com>
 * @since    1.0
 */
class Post extends ActiveRecord
{
    public $username;

    const COMMENT_STATUS_OPEN = 'open';
    const COMMENT_STATUS_CLOSE = 'close';

    const POST_STATUS_PUBLISH = 'publish';
    const POST_STATUS_PRIVATE = 'private';
    const POST_STATUS_DRAFT = 'draft';
    const POST_STATUS_TRASH = 'trash';
    const POST_STATUS_REVIEW = 'review';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'      => SluggableBehavior::className(),
                'attribute'  => 'post_title',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['post_slug'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_title', 'post_slug'], 'required'],
            [['post_author', 'post_type', 'post_comment_count'], 'integer'],
            [['post_title', 'post_excerpt', 'post_content'], 'string'],
            [['post_date', 'post_modified', 'post_author'], 'safe'],
            [['post_status', 'post_comment_status'], 'string', 'max' => 20],
            [['post_password', 'post_slug'], 'string', 'max' => 255],

            ['post_comment_status', 'in', 'range' => [self::COMMENT_STATUS_OPEN, self::COMMENT_STATUS_CLOSE]],
            ['post_comment_status', 'default', 'value' => self::COMMENT_STATUS_CLOSE],
            ['post_comment_count', 'default', 'value' => 0],
            ['post_status', 'in', 'range' => [self::POST_STATUS_PUBLISH, self::POST_STATUS_DRAFT, self::POST_STATUS_PRIVATE, self::POST_STATUS_REVIEW, self::POST_STATUS_TRASH]],
            ['post_status', 'default', 'value' => self::POST_STATUS_PUBLISH],
            [['post_title', 'post_slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => Yii::t('writesdown', 'ID'),
            'post_author'         => Yii::t('writesdown', 'Author'),
            'post_type'           => Yii::t('writesdown', 'Type'),
            'post_title'          => Yii::t('writesdown', 'Title'),
            'post_excerpt'        => Yii::t('writesdown', 'Excerpt'),
            'post_content'        => Yii::t('writesdown', 'Content'),
            'post_date'           => Yii::t('writesdown', 'Date'),
            'post_modified'       => Yii::t('writesdown', 'Modified'),
            'post_status'         => Yii::t('writesdown', 'Status'),
            'post_password'       => Yii::t('writesdown', 'Password'),
            'post_slug'           => Yii::t('writesdown', 'Slug'),
            'post_comment_status' => Yii::t('writesdown', 'Comment Status'),
            'post_comment_count'  => Yii::t('writesdown', 'Comment Count'),
            'username'            => Yii::t('writesdown', 'Author'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['media_post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostType()
    {
        return $this->hasOne(PostType::className(), ['id' => 'post_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'post_author']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostComments()
    {
        return $this->hasMany(PostComment::className(), ['comment_post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostMeta()
    {
        return $this->hasMany(PostMeta::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermRelationships()
    {
        return $this->hasMany(TermRelationship::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerms()
    {
        return $this->hasMany(Term::className(), ['id' => 'term_id'])->viaTable('{{%term_relationship}}', ['post_id' => 'id']);
    }

    /**
     * Get post status as array.
     *
     * @return array
     */
    public function getPostStatus()
    {
        return [
            self::POST_STATUS_PUBLISH => "Publish",
            self::POST_STATUS_DRAFT   => "Draft",
            self::POST_STATUS_PRIVATE => "Private",
            self::POST_STATUS_TRASH   => "Trash",
            self::POST_STATUS_REVIEW  => "Review",
        ];
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
     * Get permalink of current post
     *
     * @return string
     */
    public function getUrl()
    {
        return Yii::$app->urlManagerFront->createAbsoluteUrl(['/post/view', 'id' => $this->id]);

    }

    /**
     * Get meta for current media.
     *
     * @param $meta_name
     *
     * @return bool|mixed
     */
    public function getMeta($meta_name)
    {
        $model = PostMeta::find()->andWhere(['meta_name' => $meta_name])->andWhere(['media_id' => $this->id])->one();

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

        $model = new PostMeta();
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
        $model = PostMeta::find()->andWhere(['meta_name' => $meta_name])->andWhere(['media_id' => $this->id])->one();

        if (is_array($meta_value) || is_object($meta_value))
            $meta_value = Json::encode($meta_value);

        $model->meta_value = $meta_value;

        return $model->save();
    }

    /**
     * @param bool $sameType
     * @param bool $sameTerm
     *
     * @return array|null|Post
     */
    public function getNextPost($sameType = true, $sameTerm = false)
    {
        /* @var $query \yii\db\ActiveQuery */
        $query = static::find()->from(['post' => $this->tableName()])->andWhere(['>', 'post.id', $this->id])->andWhere(['post_status' => 'publish'])->orderBy(['post.id' => SORT_ASC]);
        if ($sameType) {
            $query->andWhere(['post_type' => $this->post_type]);
        }
        if ($sameTerm) {
            $query->innerJoinWith(['terms' => function ($query) {
                /* @var $query \yii\db\ActiveQuery */
                $query->from(['term' => Term::tableName()])->andWhere(['IN', 'term.id', implode(',', ArrayHelper::getColumn($this->terms, 'id'))]);
            }]);
        }

        return $query->one();
    }

    /**
     * @param bool   $sameType
     * @param bool   $sameTerm
     * @param string $title
     * @param array  $options
     *
     * @return string
     */
    public function getNextPostLink($sameType = true, $sameTerm = false, $title = '{post_title}', $options = [])
    {
        if ($nextPost = $this->getNextPost($sameType, $sameTerm)) {
            $title = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($nextPost) {
                $attribute = $matches[1];

                return $nextPost->{$attribute};
            }, $title);

            return Html::a($title, $nextPost->url, $options);
        }

        return '';
    }

    /**
     * @param bool $sameType
     * @param bool $sameTerm
     *
     * @return array|null|Post
     */
    public function getPrevPost($sameType = true, $sameTerm = false)
    {
        /* @var $query \yii\db\ActiveQuery */
        $query = static::find()->from(['post' => $this->tableName()])->andWhere(['<', 'post.id', $this->id])->andWhere(['post_status' => 'publish'])->orderBy(['post.id' => SORT_DESC]);
        if ($sameType) {
            $query->andWhere(['post_type' => $this->post_type]);
        }
        if ($sameTerm) {
            $query->innerJoinWith(['terms' => function ($query) {
                /* @var $query \yii\db\ActiveQuery */
                $query->from(['term' => Term::tableName()])->andWhere(['IN', 'term.id', implode(',', ArrayHelper::getColumn($this->terms, 'id'))]);
            }]);
        }

        return $query->one();
    }

    /**
     * @param bool   $sameType
     * @param bool   $sameTerm
     * @param string $title
     * @param array  $options
     *
     * @return string
     */
    public function getPrevPostLink($sameType = true, $sameTerm = false, $title = 'PREV {post_title}', $options = [])
    {
        if ($nextPost = $this->getPrevPost($sameType, $sameTerm)) {
            $title = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($nextPost) {
                $attribute = $matches[1];

                return $nextPost->{$attribute};
            }, $title);

            return Html::a($title, $nextPost->url, $options);
        }

        return '';
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->post_author = Yii::$app->user->id;
            }
            $this->post_modified = new Expression('NOW()');
            $this->post_excerpt = substr(strip_tags($this->post_content), 0, 400);

            return true;
        } else {
            return false;
        }
    }
}