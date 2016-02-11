<?php

namespace common\models\search;

use common\models\Module as ModuleModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Module represents the model behind the search form about `common\models\Module`.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.2.0
 */
class Module extends ModuleModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'backend_bootstrap', 'frontend_bootstrap'], 'integer'],
            [['name', 'title', 'description', 'config', 'directory', 'date', 'modified'], 'safe'],
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
        $query = ModuleModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'backend_bootstrap' => $this->backend_bootstrap,
            'frontend_bootstrap' => $this->frontend_bootstrap,
            'date' => $this->date,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'config', $this->config])
            ->andFilterWhere(['like', 'directory', $this->directory]);

        return $dataProvider;
    }
}
