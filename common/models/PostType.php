<?php
/**
 * @file      PostType.php.
 * @date      6/4/2015
 * @time      4:33 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%post_type}}".
 *
 * @property integer            $id
 * @property string             $post_type_name
 * @property string             $post_type_slug
 * @property string             $post_type_description
 * @property string             $post_type_icon
 * @property string             $post_type_sn
 * @property string             $post_type_pn
 * @property integer            $post_type_smb
 * @property string             $post_type_permission
 *
 * @property Post[]             $posts
 * @property PostTypeTaxonomy[] $postTypeTaxonomies
 * @property Taxonomy[]         $taxonomies
 *
 * @package common\models
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class PostType extends ActiveRecord
{
    const SMB = 1;
    const NON_SMB = 0;

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
                'class'      => SluggableBehavior::className(),
                'attribute'  => 'post_type_name',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['post_type_slug'],
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
            [['post_type_name', 'post_type_slug', 'post_type_sn', 'post_type_pn', 'post_type_permission'], 'required'],
            [['post_type_description'], 'string'],
            [['post_type_smb'], 'integer'],
            ['post_type_smb', 'in', 'range' => [self::SMB, self::NON_SMB]],
            ['post_type_smb', 'default', 'value' => self::NON_SMB],
            [['post_type_name', 'post_type_slug', 'post_type_permission'], 'string', 'max' => 64],
            [['post_type_icon', 'post_type_sn', 'post_type_pn'], 'string', 'max' => 255],
            [['post_type_name', 'post_type_slug'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('writesdown', 'ID'),
            'post_type_name'        => Yii::t('writesdown', 'Name'),
            'post_type_slug'        => Yii::t('writesdown', 'Slug'),
            'post_type_description' => Yii::t('writesdown', 'Description'),
            'post_type_icon'        => Yii::t('writesdown', 'Icon'),
            'post_type_sn'          => Yii::t('writesdown', 'Singular Name'),
            'post_type_pn'          => Yii::t('writesdown', 'Plural Name'),
            'post_type_smb'         => Yii::t('writesdown', 'Show Menu Builder'),
            'post_type_permission'  => Yii::t('writesdown', 'Permission'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['post_type' => 'id']);
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
        return $this->hasMany(Taxonomy::className(), ['id' => 'taxonomy_id'])->viaTable('{{%post_type_taxonomy}}', ['post_type_id' => 'id']);
    }

    /**
     * Get array of smb hierarchical for label or dropdown.
     * SMB is abbreviation from Show Menu Builder.
     *
     * @return array
     */
    public function getSmb()
    {
        return [
            self::SMB     => "Yes",
            self::NON_SMB => "No"
        ];
    }

    /**
     * Get all post type which post_type_smb is true.
     * The return value will be used in admin sidebar menu.
     *
     * @param int $position Position of post type in admin site menu
     *
     * @return array
     */
    public static function getMenu($position = 2)
    {
        /* @var $model \common\models\PostType */
        $models = static::find()->all();
        $adminSiteMenu = [];
        foreach ($models as $model) {
            $adminSiteMenu[ $position ] = [
                'label'   => $model->post_type_pn,
                'icon'    => '<i class="' . $model->post_type_icon . '"></i>',
                'options' => [
                    'class' => 'treeview'
                ],
                'items'   => self::getTaxonomyMenu($model),
                'visible' => Yii::$app->user->can($model->post_type_permission)
            ];
            $position++;
        }

        return $adminSiteMenu;
    }

    /**
     * Get all taxonomies in postType to show as submenu.
     *
     * @param \common\models\PostType $postType
     *
     * @return array
     */
    protected static function getTaxonomyMenu($postType)
    {
        $adminSiteSubmenu = [];
        $adminSiteSubmenu[] = ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('app', 'All {post_type_name}', ['post_type_name' => $postType->post_type_pn]), 'url' => ['/post/index/', 'post_type' => $postType->id]];
        $adminSiteSubmenu[] = ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('app', 'Add New {post_type_name}', ['post_type_name' => $postType->post_type_sn]), 'url' => ['/post/create/', 'post_type' => $postType->id]];
        foreach ($postType->taxonomies as $taxonomy) {
            $adminSiteSubmenu[] = ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => $taxonomy->taxonomy_pn, 'url' => ['/taxonomy/view/', 'id' => $taxonomy->id], 'visible' => Yii::$app->user->can('editor')];
        }
        $adminSiteSubmenu[] = ['icon' => '<i class="fa fa-circle-o"></i>', 'label' => Yii::t('app', 'Comments'), 'url' => ['/post-comment/index/', 'post_type' => $postType->id], 'visible' => Yii::$app->user->can('editor')];

        return $adminSiteSubmenu;

    }
}