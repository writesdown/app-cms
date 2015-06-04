<?php
/**
 * @file      Taxonomy.php.
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
use common\models\Taxonomy as TaxonomyModel;

/**
 * Taxonomy represents the model behind the search form about `common\models\Taxonomy`.
 *
 * @package common\models\search
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class Taxonomy extends TaxonomyModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'taxonomy_hierarchical', 'taxonomy_smb'], 'integer'],
            [['taxonomy_name', 'taxonomy_slug', 'taxonomy_sn', 'taxonomy_pn'], 'safe'],
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
        $query = TaxonomyModel::find();

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
            'id'                    => $this->id,
            'taxonomy_hierarchical' => $this->taxonomy_hierarchical,
            'taxonomy_smb'          => $this->taxonomy_smb,
        ]);

        $query->andFilterWhere(['like', 'taxonomy_name', $this->taxonomy_name])
            ->andFilterWhere(['like', 'taxonomy_slug', $this->taxonomy_slug])
            ->andFilterWhere(['like', 'taxonomy_sn', $this->taxonomy_sn])
            ->andFilterWhere(['like', 'taxonomy_pn', $this->taxonomy_pn]);

        return $dataProvider;
    }
}
