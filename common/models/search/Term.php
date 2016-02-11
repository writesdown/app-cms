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
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class Term extends TermModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'taxonomy_id', 'parent', 'count'], 'integer'],
            [['name', 'slug', 'description'], 'safe'],
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
     * @param int $taxonomyId
     * @return ActiveDataProvider
     */
    public function search($params, $taxonomyId)
    {
        $query = TermModel::find();

        $query->andWhere(['taxonomy_id' => $taxonomyId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
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
            'id' => $this->id,
            'taxonomy_id' => $this->taxonomy_id,
            'parent' => $this->parent,
            'count' => $this->count,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
