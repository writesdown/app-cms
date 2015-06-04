<?php
/**
 * @file      MediaComment.php.
 * @date      6/4/2015
 * @time      4:55 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/* MODEL */
use common\models\MediaComment as MediaCommentModel;

/**
 * MediaComment represents the model behind the search form about `common\models\MediaComment`.
 *
 * @package common\models\search
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MediaComment extends MediaCommentModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'comment_media_id', 'comment_parent', 'comment_user_id'], 'integer'],
            [['comment_author', 'comment_author_email', 'comment_author_url', 'comment_author_ip', 'comment_date', 'comment_content', 'comment_approved', 'comment_agent', 'media_title'], 'safe'],
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
     * @param int   $media_id
     *
     * @return ActiveDataProvider
     */
    public function search($params, $media_id)
    {
        $query = MediaCommentModel::find();
        $query->innerJoinWith(['commentMedia' => function ($query) {
            /* @var $query \yii\db\ActiveQuery */
            return $query->from(['media' => Media::tableName()]);
        }]);

        if ($media_id) {
            $query->andWhere(['media.id' => $media_id]);
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
            'id'               => $this->id,
            'comment_media_id' => $this->comment_media_id,
            'comment_parent'   => $this->comment_parent,
            'comment_user_id'  => $this->comment_user_id,
        ]);

        $query->andFilterWhere(['like', 'comment_author', $this->comment_author])
            ->andFilterWhere(['like', 'comment_author_email', $this->comment_author_email])
            ->andFilterWhere(['like', 'comment_author_url', $this->comment_author_url])
            ->andFilterWhere(['like', 'comment_author_ip', $this->comment_author_ip])
            ->andFilterWhere(['like', 'comment_content', $this->comment_content])
            ->andFilterWhere(['like', 'comment_approved', $this->comment_approved])
            ->andFilterWhere(['like', 'comment_agent', $this->comment_agent])
            ->andFilterWhere(['like', 'comment_date', $this->comment_date])
            ->andFilterWhere(['like', 'media.media_title', $this->media_title]);

        return $dataProvider;
    }
}
