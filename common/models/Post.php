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
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property integer $author
 * @property integer $type
 * @property string $title
 * @property string $excerpt
 * @property string $content
 * @property string $date
 * @property string $modified
 * @property string $status
 * @property string $password
 * @property string $slug
 * @property string $comment_status
 * @property integer $comment_count
 * @property string $url
 *
 * @property Media[] $media
 * @property PostType $postType
 * @property User $postAuthor
 * @property PostComment[] $postComments
 * @property PostMeta[] $postMeta
 * @property TermRelationship[] $termRelationships
 * @property Term[] $terms
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class Post extends ActiveRecord
{
    public $username;

    const COMMENT_STATUS_OPEN = 'open';
    const COMMENT_STATUS_CLOSE = 'close';
    const STATUS_PUBLISH = 'publish';
    const STATUS_PRIVATE = 'private';
    const STATUS_DRAFT = 'draft';
    const STATUS_TRASH = 'trash';
    const STATUS_REVIEW = 'review';

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
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => ['slug']],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            [['author', 'type', 'comment_count'], 'integer'],
            [['title', 'excerpt', 'content'], 'string'],
            [['date', 'modified', 'author'], 'safe'],
            [['status', 'comment_status'], 'string', 'max' => 20],
            [['password', 'slug'], 'string', 'max' => 255],
            ['comment_status', 'in', 'range' => [self::COMMENT_STATUS_OPEN, self::COMMENT_STATUS_CLOSE]],
            ['comment_status', 'default', 'value' => self::COMMENT_STATUS_CLOSE],
            ['comment_count', 'default', 'value' => 0],
            [
                'status',
                'in',
                'range' => [
                    self::STATUS_PUBLISH,
                    self::STATUS_DRAFT,
                    self::STATUS_PRIVATE,
                    self::STATUS_REVIEW,
                    self::STATUS_TRASH,
                ],
            ],
            ['status', 'default', 'value' => self::STATUS_PUBLISH],
            [['title', 'slug'], 'unique'],
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
            'type' => Yii::t('writesdown', 'Type'),
            'title' => Yii::t('writesdown', 'Title'),
            'excerpt' => Yii::t('writesdown', 'Excerpt'),
            'content' => Yii::t('writesdown', 'Content'),
            'date' => Yii::t('writesdown', 'Date'),
            'modified' => Yii::t('writesdown', 'Modified'),
            'status' => Yii::t('writesdown', 'Status'),
            'password' => Yii::t('writesdown', 'Password'),
            'slug' => Yii::t('writesdown', 'Slug'),
            'comment_status' => Yii::t('writesdown', 'Comment Status'),
            'comment_count' => Yii::t('writesdown', 'Comment Count'),
            'username' => Yii::t('writesdown', 'Author'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostType()
    {
        return $this->hasOne(PostType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostComments()
    {
        return $this->hasMany(PostComment::className(), ['post_id' => 'id']);
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
        return $this
            ->hasMany(Term::className(), ['id' => 'term_id'])
            ->viaTable('{{%term_relationship}}', ['post_id' => 'id']);
    }

    /**
     * Get post status as array.
     *
     * @return array
     */
    public function getPostStatuses()
    {
        return [
            self::STATUS_PUBLISH => Yii::t('writesdown', 'Publish'),
            self::STATUS_DRAFT => Yii::t('writesdown', 'Draft'),
            self::STATUS_PRIVATE => Yii::t('writesdown', 'Private'),
            self::STATUS_TRASH => Yii::t('writesdown', 'Trash'),
            self::STATUS_REVIEW => Yii::t('writesdown', 'Review'),
        ];
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
     * Get permalink of current post.
     *
     * @return string
     */
    public function getUrl()
    {
        return Yii::$app->urlManagerFront->createAbsoluteUrl(['/post/view', 'id' => $this->id]);
    }

    /**
     * Get meta for current post.
     *
     * @param string $name
     * @return mixed|null
     */
    public function getMeta($name)
    {
        /* @var $model \common\models\PostMeta */
        $model = PostMeta::findOne(['name' => $name, 'post_id' => $this->id]);

        if ($model) {
            if (Json::isJson($model->value)) {
                return Json::decode($model->value);
            }

            return $model->value;
        }

        return null;
    }

    /**
     * Add new meta data for current post.
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

        $model = new PostMeta([
            'post_id' => $this->id,
            'name' => $name,
            'value' => $value,
        ]);

        return $model->save();
    }

    /**
     * Update meta data for current post.
     *
     * @param string $name
     * @param string|array $value
     * @return bool
     */
    public function upMeta($name, $value)
    {
        /* @var $model \common\models\PostMeta */
        $model = PostMeta::findOne(['name' => $name, 'post_id' => $this->id]);

        if (is_array($value) || is_object($value)) {
            $value = Json::encode($value);
        }

        $model->value = $value;

        return $model->save();
    }

    /**
     * @param bool $sameType
     * @param bool $sameTerm
     * @return array|null|Post
     */
    public function getNextPost($sameType = true, $sameTerm = false)
    {
        /* @var $query \yii\db\ActiveQuery */
        $query = static::find()
            ->from(['post' => $this->tableName()])
            ->andWhere(['>', 'post.id', $this->id])
            ->andWhere(['status' => 'publish'])
            ->orderBy(['post.id' => SORT_ASC]);

        if ($sameType) {
            $query->andWhere(['type' => $this->type]);
        }

        if ($sameTerm) {
            $query->innerJoinWith([
                'terms' => function ($query) {
                    /* @var $query \yii\db\ActiveQuery */
                    $query->from(['term' => Term::tableName()])->andWhere([
                        'IN',
                        'term.id',
                        implode(',', ArrayHelper::getColumn($this->terms, 'id')),
                    ]);
                },
            ]);
        }

        return $query->one();
    }

    /**
     * @param bool $sameType
     * @param bool $sameTerm
     * @param string $title
     * @param array $options
     * @return string
     */
    public function getNextPostLink($title = '{title}', $sameType = true, $sameTerm = false, $options = [])
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
     * @return array|null|Post
     */
    public function getPrevPost($sameType = true, $sameTerm = false)
    {
        /* @var $query \yii\db\ActiveQuery */
        $query = static::find()
            ->from(['post' => $this->tableName()])
            ->andWhere(['<', 'post.id', $this->id])
            ->andWhere(['status' => 'publish'])
            ->orderBy(['post.id' => SORT_DESC]);

        if ($sameType) {
            $query->andWhere(['type' => $this->type]);
        }

        if ($sameTerm) {
            $query->innerJoinWith([
                'terms' => function ($query) {
                    /* @var $query \yii\db\ActiveQuery */
                    $query->from(['term' => Term::tableName()])->andWhere([
                        'IN',
                        'term.id',
                        implode(',', ArrayHelper::getColumn($this->terms, 'id')),
                    ]);
                },
            ]);
        }

        return $query->one();
    }

    /**
     * @param bool $sameType
     * @param bool $sameTerm
     * @param string $title
     * @param array $options
     * @return string
     */
    public function getPrevPostLink($title = '{title}', $sameType = true, $sameTerm = false, $options = [])
    {
        if ($prevPost = $this->getPrevPost($sameType, $sameTerm)) {
            $title = preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($prevPost) {
                $attribute = $matches[1];

                return $prevPost->{$attribute};
            }, $title);

            return Html::a($title, $prevPost->url, $options);
        }

        return '';
    }

    /**
     * Generate excerpt of post model.
     *
     * @param int $limit
     * @return string
     */
    public function getExcerpt($limit = 55)
    {
        $excerpt = preg_replace('/\s{3,}/', ' ', strip_tags($this->content));
        $words = preg_split("/[\n\r\t ]+/", $excerpt, $limit + 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE);

        if (count($words) > $limit) {
            end($words);
            $lastWord = prev($words);

            $excerpt = substr($excerpt, 0, $lastWord[1] + strlen($lastWord[0]));
        }

        return $excerpt;
    }


    /**
     * Get permission to access model by current user.
     * @return bool
     */
    public function getPermission()
    {
        if (!$this->postType
            || !Yii::$app->user->can($this->postType->permission)
            || (!Yii::$app->user->can('editor') && Yii::$app->user->id !== $this->author)
            || (!Yii::$app->user->can('author') && $this->status === self::STATUS_REVIEW)
        ) {
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
            }
            $this->modified = date('Y-m-d H:i:s');
            $this->excerpt = $this->getExcerpt();

            return true;
        }

        return false;
    }
}
