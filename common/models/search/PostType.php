<?php
/**
 * @file      PostType.php.
 * @date      6/4/2015
 * @time      4:58 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/* MODEL */
use common\models\PostType as PostTypeModel;

/**
 * PostType represents the model behind the search form about `common\models\PostType`.
 *
 * @package common\models\search
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class PostType extends PostTypeModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'post_type_smb'], 'integer'],
            [['post_type_name', 'post_type_slug', 'post_type_description', 'post_type_icon', 'post_type_sn', 'post_type_pn', 'post_type_permission'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PostTypeModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'            => $this->id,
            'post_type_smb' => $this->post_type_smb,
        ]);

        $query->andFilterWhere(['like', 'post_type_name', $this->post_type_name])
            ->andFilterWhere(['like', 'post_type_slug', $this->post_type_slug])
            ->andFilterWhere(['like', 'post_type_description', $this->post_type_description])
            ->andFilterWhere(['like', 'post_type_icon', $this->post_type_icon])
            ->andFilterWhere(['like', 'post_type_sn', $this->post_type_sn])
            ->andFilterWhere(['like', 'post_type_pn', $this->post_type_pn])
            ->andFilterWhere(['like', 'post_type_permission', $this->post_type_permission]);

        return $dataProvider;
    }
}