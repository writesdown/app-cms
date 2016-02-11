<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\MediaComment as MediaCommentModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MediaComment represents the model behind the search form about `common\models\MediaComment`.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class MediaComment extends MediaCommentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'media_id', 'parent', 'user_id'], 'integer'],
            [['author', 'email', 'url', 'ip', 'date', 'content', 'status', 'agent', 'media_title'], 'safe'],
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
     * @param int|null $mediaId
     * @return ActiveDataProvider
     */
    public function search($params, $mediaId = null)
    {
        $query = MediaCommentModel::find();
        $query->innerJoinWith([
            'commentMedia' => function ($query) {
                /* @var $query \yii\db\ActiveQuery */
                return $query->from(['media' => Media::tableName()]);
            },
        ])->from(['mediaComment' => static::tableName()]);

        if ($mediaId) {
            $query->andWhere(['media.id' => $mediaId]);
        }

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
            'media_id' => $this->media_id,
            'parent' => $this->parent,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'mediaComment.author', $this->author])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'mediaComment.content', $this->content])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'mediaComment.date', $this->date])
            ->andFilterWhere(['like', 'media.title', $this->media_title]);

        return $dataProvider;
    }
}
