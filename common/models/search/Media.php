<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\models\search;

use common\models\Media as MediaModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Media represents the model behind the search form about `common\models\Media`.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class Media extends MediaModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author', 'post_id', 'comment_count'], 'integer'],
            [
                [
                    'title',
                    'excerpt',
                    'content',
                    'password',
                    'date',
                    'modified',
                    'slug',
                    'mime_type',
                    'comment_status',
                    'username',
                    'post_title',
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MediaModel::find();
        $query->innerJoinWith([
            'mediaAuthor' => function ($query) {
                /* @var $query \yii\db\ActiveQuery */
                return $query->from(['user' => User::tableName()]);
            },
        ])->from(['media' => $this->tableName()]);
        $query->leftJoin(['post' => Post::tableName()], 'media.post_id = post.id');

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
            'media.id' => $this->id,
            'media.author' => $this->author,
            'post_id' => $this->post_id,
            'media.comment_count' => $this->comment_count,
        ]);

        $query->andFilterWhere(['like', 'media.title', $this->title])
            ->andFilterWhere(['like', 'media.excerpt', $this->excerpt])
            ->andFilterWhere(['like', 'media.content', $this->content])
            ->andFilterWhere(['like', 'media.password', $this->password])
            ->andFilterWhere(['like', 'media.slug', $this->slug])
            ->andFilterWhere(['like', 'mime_type', $this->mime_type])
            ->andFilterWhere(['like', 'media.comment_status', $this->comment_status])
            ->andFilterWhere(['like', 'media.date', $this->date])
            ->andFilterWhere(['like', 'media.modified', $this->modified])
            ->andFilterWhere(['like', 'post.title', $this->post_title])
            ->andFilterWhere(['like', 'user.username', $this->username]);

        return $dataProvider;
    }
}
