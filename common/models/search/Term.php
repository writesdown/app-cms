<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\Term as TermModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Term represents the model behind the search form about `common\models\Term`.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class Term extends TermModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'taxonomy_id', 'term_parent', 'term_count'], 'integer'],
            [['term_name', 'term_slug', 'term_description'], 'safe'],
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
     * @param int   $taxonomy_id
     *
     * @return ActiveDataProvider
     */
    public function search($params, $taxonomy_id)
    {
        $query = TermModel::find();

        $query->andWhere(['taxonomy_id' => $taxonomy_id]);

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
            'id'          => $this->id,
            'taxonomy_id' => $this->taxonomy_id,
            'term_parent' => $this->term_parent,
            'term_count'  => $this->term_count,
        ]);

        $query->andFilterWhere(['like', 'term_name', $this->term_name])
            ->andFilterWhere(['like', 'term_slug', $this->term_slug])
            ->andFilterWhere(['like', 'term_description', $this->term_description]);

        return $dataProvider;
    }
}
