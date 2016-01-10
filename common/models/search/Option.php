<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\Option as OptionModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Option represents the model behind the search form about `common\models\Option`.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class Option extends OptionModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['option_name', 'option_value', 'option_label', 'option_group'], 'safe'],
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
        $query = OptionModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'option_name', $this->option_name])
            ->andFilterWhere(['like', 'option_value', $this->option_value])
            ->andFilterWhere(['like', 'option_label', $this->option_label])
            ->andFilterWhere(['like', 'option_group', $this->option_group]);

        return $dataProvider;
    }
}
