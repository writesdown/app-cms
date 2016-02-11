<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\Post as PostModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Post represents the model behind the search form about `common\models\Post`.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class Post extends PostModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author', 'type', 'comment_count'], 'integer'],
            [
                [
                    'title',
                    'excerpt',
                    'content',
                    'date',
                    'modified',
                    'status',
                    'password',
                    'slug',
                    'comment_status',
                    'username',
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
     * @param int $type
     * @param null|string $user
     *
     * @return ActiveDataProvider
     */
    public function search($params, $type, $user = null)
    {
        $query = PostModel::find();
        $query->innerJoinWith(['postAuthor'])->from(['post' => static::tableName()]);
        $query->andWhere(['type' => $type]);

        if ($user) {
            $query->andWhere(['author' => $user]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => ArrayHelper::merge($dataProvider->sort->attributes, [
                'username' => [
                    'asc' => ['username' => SORT_ASC],
                    'desc' => ['username' => SORT_DESC],
                    'label' => 'Author',
                    'value' => 'username',
                ],
            ]),
            'defaultOrder' => ['id' => SORT_DESC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'post.id' => $this->id,
            'author' => $this->author,
            'type' => $this->type,
            'comment_count' => $this->comment_count,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'excerpt', $this->excerpt])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'post.status', $this->status])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'modified', $this->modified])
            ->andFilterWhere(['like', 'comment_status', $this->comment_status])
            ->andFilterWhere(['like', 'username', $this->username]);


        return $dataProvider;
    }
}
