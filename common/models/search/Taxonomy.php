<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\Taxonomy as TaxonomyModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Taxonomy represents the model behind the search form about `common\models\Taxonomy`.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class Taxonomy extends TaxonomyModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hierarchical', 'menu_builder'], 'integer'],
            [['name', 'slug', 'singular_name', 'plural_name'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TaxonomyModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'hierarchical' => $this->hierarchical,
            'menu_builder' => $this->menu_builder,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'singular_name', $this->singular_name])
            ->andFilterWhere(['like', 'plural_name', $this->plural_name]);

        return $dataProvider;
    }
}
