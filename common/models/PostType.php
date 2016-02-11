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
 * This is the model class for table "{{%post_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $icon
 * @property string $singular_name
 * @property string $plural_name
 * @property integer $menu_builder
 * @property string $permission
 *
 * @property Post[] $posts
 * @property PostTypeTaxonomy[] $postTypeTaxonomies
 * @property Taxonomy[] $taxonomies
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class PostType extends ActiveRecord
{
    const MENU_BUILDER = 1;
    const NOT_MENU_BUILDER = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_type}}';
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
            [['name', 'singular_name', 'plural_name', 'permission'], 'required'],
            [['description'], 'string'],
            [['menu_builder'], 'integer'],
            ['menu_builder', 'in', 'range' => [self::MENU_BUILDER, self::NOT_MENU_BUILDER]],
            ['menu_builder', 'default', 'value' => self::NOT_MENU_BUILDER],
            [['name', 'slug', 'permission'], 'string', 'max' => 64],
            [['icon', 'singular_name', 'plural_name'], 'string', 'max' => 255],
            [['name', 'slug'], 'unique'],
            [['slug'], 'safe'],
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
            'description' => Yii::t('writesdown', 'Description'),
            'icon' => Yii::t('writesdown', 'Icon'),
            'singular_name' => Yii::t('writesdown', 'Singular Name'),
            'plural_name' => Yii::t('writesdown', 'Plural Name'),
            'menu_builder' => Yii::t('writesdown', 'Is Menu Builder'),
            'permission' => Yii::t('writesdown', 'Permission'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['type' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTypeTaxonomies()
    {
        return $this->hasMany(PostTypeTaxonomy::className(), ['post_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxonomies()
    {
        return $this->hasMany(Taxonomy::className(), ['id' => 'taxonomy_id'])
            ->viaTable('{{%post_type_taxonomy}}', ['post_type_id' => 'id']);
    }

    /**
     * Get array of menu_builder hierarchical for label or dropdown.
     * SMB is abbreviation from Show Menu Builder.
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

    /**
     * Get all post type which menu_builder is true.
     * The return value will be used in admin sidebar menu.
     *
     * @param int $position Position of post type in admin site menu
     * @return array
     */
    public static function getMenus($position = 2)
    {
        /* @var $model \common\models\PostType */
        $models = static::find()->all();
        $items = [];
        foreach ($models as $model) {
            $items[$position] = [
                'label' => $model->plural_name,
                'icon' => $model->icon,
                'items' => static::getTaxonomyMenus($model),
                'visible' => Yii::$app->user->can($model->permission),
            ];
            $position++;
        }

        return $items;
    }

    /**
     * Get all taxonomies in postType to show as submenu.
     *
     * @param \common\models\PostType $postType
     * @return array
     */
    protected static function getTaxonomyMenus($postType)
    {
        $items = [];
        $items[] = [
            'icon' => 'fa fa-circle-o',
            'label' => Yii::t('app', 'All {name}', ['name' => $postType->plural_name]),
            'url' => ['/post/index/', 'type' => $postType->id],
        ];
        $items[] = [
            'icon' => 'fa fa-circle-o',
            'label' => Yii::t('app', 'Add New {name}', ['name' => $postType->singular_name]),
            'url' => ['/post/create/', 'type' => $postType->id],
        ];
        foreach ($postType->taxonomies as $taxonomy) {
            $items[] = [
                'icon' => 'fa fa-circle-o',
                'label' => $taxonomy->plural_name,
                'url' => ['/taxonomy/view/', 'id' => $taxonomy->id],
                'visible' => Yii::$app->user->can('editor'),
            ];
        }
        $items[] = [
            'icon' => 'fa fa-circle-o',
            'label' => Yii::t('app', 'Comments'),
            'url' => ['/post-comment/index/', 'posttype' => $postType->id],
            'visible' => Yii::$app->user->can('editor'),
        ];

        return $items;
    }
}
