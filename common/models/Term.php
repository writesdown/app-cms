<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%term}}".
 *
 * @property integer $id
 * @property integer $taxonomy_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property integer $parent
 * @property integer $count
 * @property string $url
 *
 * @property Taxonomy $taxonomy
 * @property TermRelationship[] $termRelationships
 * @property Post[] $posts
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class Term extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%term}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
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
            [['taxonomy_id', 'name'], 'required'],
            [['taxonomy_id', 'parent', 'count'], 'integer'],
            ['description', 'string'],
            [['name', 'slug'], 'string', 'max' => 200],
            ['name', 'unique'],
            ['slug', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('writesdown', 'ID'),
            'taxonomy_id' => Yii::t('writesdown', 'Taxonomy ID'),
            'name' => Yii::t('writesdown', 'Name'),
            'slug' => Yii::t('writesdown', 'Slug'),
            'description' => Yii::t('writesdown', 'Description'),
            'parent' => Yii::t('writesdown', 'Parent'),
            'count' => Yii::t('writesdown', 'Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxonomy()
    {
        return $this->hasOne(Taxonomy::className(), ['id' => 'taxonomy_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermRelationships()
    {
        return $this->hasMany(TermRelationship::className(), ['term_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])
            ->viaTable('{{%term_relationship}}', ['term_id' => 'id']);
    }

    /**
     * Get URL of current term
     */
    public function getUrl()
    {
        return Yii::$app->urlManagerFront->createAbsoluteUrl(['/term/view', 'id' => $this->id]);
    }
}
