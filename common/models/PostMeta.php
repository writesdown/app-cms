<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%post_meta}}".
 *
 * @property integer $id
 * @property integer $post_id
 * @property string  $meta_name
 * @property string  $meta_value
 *
 * @property Post    $post
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class PostMeta extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_meta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'meta_name', 'meta_value'], 'required'],
            [['post_id'], 'integer'],
            [['meta_value'], 'string'],
            [['meta_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('writesdown', 'ID'),
            'post_id'    => Yii::t('writesdown', 'Post ID'),
            'meta_name'  => Yii::t('writesdown', 'Meta Name'),
            'meta_value' => Yii::t('writesdown', 'Meta Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
