<?php

namespace common\models\search;

use common\models\Module as ModuleModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Module represents the model behind the search form about `common\models\Module`.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class Module extends ModuleModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'module_status', 'module_bb', 'module_fb'], 'integer'],
            [
                [
                    'module_name',
                    'module_title',
                    'module_description',
                    'module_config',
                    'module_dir',
                    'module_date',
                    'module_modified',
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
        $query = ModuleModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'              => $this->id,
            'module_status'   => $this->module_status,
            'module_bb'       => $this->module_bb,
            'module_fb'       => $this->module_fb,
            'module_date'     => $this->module_date,
            'module_modified' => $this->module_modified,
        ]);

        $query->andFilterWhere(['like', 'module_name', $this->module_name])
            ->andFilterWhere(['like', 'module_title', $this->module_title])
            ->andFilterWhere(['like', 'module_description', $this->module_description])
            ->andFilterWhere(['like', 'module_config', $this->module_config])
            ->andFilterWhere(['like', 'module_dir', $this->module_dir]);

        return $dataProvider;
    }
}
