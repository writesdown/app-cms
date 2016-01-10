<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\PostType as PostTypeModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostType represents the model behind the search form about `common\models\PostType`.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
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
            [
                [
                    'post_type_name',
                    'post_type_slug',
                    'post_type_description',
                    'post_type_icon',
                    'post_type_sn',
                    'post_type_pn',
                    'post_type_permission',
                ],
                'safe',
            ],
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
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
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
