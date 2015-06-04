<?php
/**
 * @file      Taxonomy.php.
 * @date      6/4/2015
 * @time      4:28 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%taxonomy}}".
 *
 * @property integer            $id
 * @property string             $taxonomy_name
 * @property string             $taxonomy_slug
 * @property integer            $taxonomy_hierarchical
 * @property string             $taxonomy_sn
 * @property string             $taxonomy_pn
 * @property integer            $taxonomy_smb
 * @property array              $smb
 * @property array              $hierarchical
 *
 * @property PostTypeTaxonomy[] $postTypeTaxonomies
 * @property PostType[]         $postTypes
 * @property Term[]             $terms
 *
 * @package  common\models
 * @author   Agiel K. Saputra <13nightevil@gmail.com>
 * @since    1.0
 */
class Taxonomy extends ActiveRecord
{
    const HIERARCHICAL = 1;
    const NON_HIERARCHICAL = 0;

    const SMB = 1;
    const NON_SMB = 0;

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
                'class'      => SluggableBehavior::className(),
                'attribute'  => 'taxonomy_name',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['taxonomy_slug'],
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
            [['taxonomy_name', 'taxonomy_sn', 'taxonomy_pn'], 'required'],
            [['taxonomy_hierarchical', 'taxonomy_smb'], 'integer'],
            ['taxonomy_hierarchical', 'in', 'range' => [self::HIERARCHICAL, self::NON_HIERARCHICAL]],
            ['taxonomy_hierarchical', 'default', 'value' => self::NON_HIERARCHICAL],
            ['taxonomy_smb', 'in', 'range' => [self::SMB, self::NON_SMB]],
            ['taxonomy_smb', 'default', 'value' => self::NON_SMB],
            [['taxonomy_name', 'taxonomy_slug'], 'string', 'max' => 200],
            [['taxonomy_sn', 'taxonomy_pn'], 'string', 'max' => 255],
            [['taxonomy_name', 'taxonomy_slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('writesdown', 'ID'),
            'taxonomy_name'         => Yii::t('writesdown', 'Name'),
            'taxonomy_slug'         => Yii::t('writesdown', 'Slug'),
            'taxonomy_hierarchical' => Yii::t('writesdown', 'Is Hierarchical'),
            'taxonomy_sn'           => Yii::t('writesdown', 'Singular Name'),
            'taxonomy_pn'           => Yii::t('writesdown', 'Plural Name'),
            'taxonomy_smb'          => Yii::t('writesdown', 'Show Menu Builder'),
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
        return $this->hasMany(PostType::className(), ['id' => 'post_type_id'])->viaTable('{{%post_type_taxonomy}}', ['taxonomy_id' => 'id']);
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
    public function getHierarchical()
    {
        return [
            self::HIERARCHICAL     => "Yes",
            self::NON_HIERARCHICAL => "No"
        ];
    }

    /**
     * Get array of smb hierarchical for label or dropdown.
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
}
