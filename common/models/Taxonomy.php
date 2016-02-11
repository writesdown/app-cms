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
 * This is the model class for table "{{%taxonomy}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $hierarchical
 * @property string $singular_name
 * @property string $plural_name
 * @property integer $menu_builder
 *
 * @property PostTypeTaxonomy[] $postTypeTaxonomies
 * @property PostType[] $postTypes
 * @property Term[] $terms
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 1.0
 */
class Taxonomy extends ActiveRecord
{
    const HIERARCHICAL = 1;
    const NOT_HIERARCHICAL = 0;
    const MENU_BUILDER = 1;
    const NOT_MENU_BUILDER = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%taxonomy}}';
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
            [['name', 'singular_name', 'plural_name'], 'required'],
            [['hierarchical', 'menu_builder'], 'integer'],
            ['hierarchical', 'in', 'range' => [self::HIERARCHICAL, self::NOT_HIERARCHICAL]],
            ['hierarchical', 'default', 'value' => self::NOT_HIERARCHICAL],
            ['menu_builder', 'in', 'range' => [self::MENU_BUILDER, self::NOT_MENU_BUILDER]],
            ['menu_builder', 'default', 'value' => self::NOT_MENU_BUILDER],
            [['name', 'slug'], 'string', 'max' => 200],
            [['singular_name', 'plural_name'], 'string', 'max' => 255],
            [['name', 'slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('writesdown', 'ID'),
            'name' => Yii::t('writesdown', 'Name'),
            'slug' => Yii::t('writesdown', 'Slug'),
            'hierarchical' => Yii::t('writesdown', 'Is Hierarchical'),
            'singular_name' => Yii::t('writesdown', 'Singular Name'),
            'plural_name' => Yii::t('writesdown', 'Plural Name'),
            'menu_builder' => Yii::t('writesdown', 'Is Menu Builder'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTypeTaxonomies()
    {
        return $this->hasMany(PostTypeTaxonomy::className(), ['taxonomy_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTypes()
    {
        return $this->hasMany(PostType::className(), ['id' => 'post_type_id'])
            ->viaTable('{{%post_type_taxonomy}}', ['taxonomy_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTerms()
    {
        return $this->hasMany(Term::className(), ['taxonomy_id' => 'id']);
    }

    /**
     * Get array of taxonomy hierarchical for label or dropdown.
     *
     * @return array
     */
    public function getHierarchies()
    {
        return [
            self::HIERARCHICAL => Yii::t('writesdown', 'Yes'),
            self::NOT_HIERARCHICAL => Yii::t('writesdown', 'No'),
        ];
    }

    /**
     * Get array of menu_builder hierarchical for label or dropdown.
     *
     * @return array
     */
    public function getMenuBuilders()
    {
        return [
            self::MENU_BUILDER => Yii::t('writesdown', 'Yes'),
            self::NOT_MENU_BUILDER => Yii::t('writesdown', 'No'),
        ];
    }
}
