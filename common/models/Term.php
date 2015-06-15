<?php
/**
 * @file    Term.php.
 * @date    6/4/2015
 * @time    4:28 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%term}}".
 *
 * @property integer            $id
 * @property integer            $taxonomy_id
 * @property string             $term_name
 * @property string             $term_slug
 * @property string             $term_description
 * @property integer            $term_parent
 * @property integer            $term_count
 * @property string             $url
 *
 * @property Taxonomy           $taxonomy
 * @property TermRelationship[] $termRelationships
 * @property Post[]             $posts
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
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
    public function behaviors(){
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'term_name',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['term_slug']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taxonomy_id', 'term_name'], 'required'],
            [['taxonomy_id', 'term_parent', 'term_count'], 'integer'],
            [['term_description'], 'string'],
            [['term_name', 'term_slug'], 'string', 'max' => 200],
            [['term_name'], 'unique'],
            [['term_slug'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('writesdown', 'ID'),
            'taxonomy_id'      => Yii::t('writesdown', 'Taxonomy ID'),
            'term_name'        => Yii::t('writesdown', 'Name'),
            'term_slug'        => Yii::t('writesdown', 'Slug'),
            'term_description' => Yii::t('writesdown', 'Description'),
            'term_parent'      => Yii::t('writesdown', 'Parent'),
            'term_count'       => Yii::t('writesdown', 'Count'),
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
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable('{{%term_relationship}}', ['term_id' => 'id']);
    }

    /**
     * Get URL of current term
     */
    public function getUrl(){
        return Yii::$app->urlManagerFront->createAbsoluteUrl(['/term/view', 'id'=> $this->id]);
    }
}