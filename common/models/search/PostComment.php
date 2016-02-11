<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\PostComment as PostCommentModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostComment represents the model behind the search form about `common\models\PostComment`.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class PostComment extends PostCommentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'post_id', 'parent', 'user_id'], 'integer'],
            [['author', 'email', 'url', 'ip', 'date', 'content', 'status', 'agent', 'post_title'], 'safe'],
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
     * @param int $posttype Post type ID
     * @param int|null $post Post ID
     * @return ActiveDataProvider
     */
    public function search($params, $posttype, $post = null)
    {
        $query = PostCommentModel::find();

        $query->innerJoinWith([
            'commentPost' => function ($query) {
                /* @var $query \yii\db\ActiveQuery */
                return $query->from(['post' => Post::tableName()]);
            },
        ])->from(['postComment' => PostComment::tableName()]);

        $query->andWhere(['post.type' => $posttype]);

        if ($post) {
            $query->andWhere(['post.id' => $post]);
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
            'post_id' => $this->post_id,
            'parent' => $this->parent,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'postComment.author', $this->author])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'postComment.content', $this->content])
            ->andFilterWhere(['like', 'postComment.status', $this->status])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'postComment.date', $this->date])
            ->andFilterWhere(['like', 'post.title', $this->post_title]);

        return $dataProvider;
    }
}
