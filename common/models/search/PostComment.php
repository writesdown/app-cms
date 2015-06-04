<?php
/**
 * @file      PostComment.php.
 * @date      6/4/2015
 * @time      4:57 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/* MODEL */
use common\models\PostComment as PostCommentModel;

/**
 * PostComment represents the model behind the search form about `common\models\PostComment`.
 *
 * @package common\models\search
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class PostComment extends PostCommentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'comment_post_id', 'comment_parent', 'comment_user_id'], 'integer'],
            [['comment_author', 'comment_author_email', 'comment_author_url', 'comment_author_ip', 'comment_date', 'comment_content', 'comment_approved', 'comment_agent', 'post_title'], 'safe'],
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
     * @param array    $params
     *
     * @param int      $post_type
     * @param int|null $post_id
     *
     * @return ActiveDataProvider
     */
    public function search($params, $post_type, $post_id = null)
    {
        $query = PostCommentModel::find();

        $query->innerJoinWith(['commentPost' => function ($query) {
            /* @var $query \yii\db\ActiveQuery */
            return $query->from(['post' => Post::tableName()]);
        }]);

        $query->andWhere(['post.post_type' => $post_type]);

        if ($post_id) {
            $query->andWhere(['post.id' => $post_id]);
        }

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
            'id'              => $this->id,
            'comment_post_id' => $this->comment_post_id,
            'comment_parent'  => $this->comment_parent,
            'comment_user_id' => $this->comment_user_id,
        ]);

        $query->andFilterWhere(['like', 'comment_author', $this->comment_author])
            ->andFilterWhere(['like', 'comment_author_email', $this->comment_author_email])
            ->andFilterWhere(['like', 'comment_author_url', $this->comment_author_url])
            ->andFilterWhere(['like', 'comment_author_ip', $this->comment_author_ip])
            ->andFilterWhere(['like', 'comment_content', $this->comment_content])
            ->andFilterWhere(['like', 'comment_approved', $this->comment_approved])
            ->andFilterWhere(['like', 'comment_agent', $this->comment_agent])
            ->andFilterWhere(['like', 'comment_date', $this->comment_date])
            ->andFilterWhere(['like', 'post.post_title', $this->post_title]);

        return $dataProvider;
    }
}