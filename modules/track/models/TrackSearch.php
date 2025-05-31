<?php

namespace app\modules\track\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\track\models\Track;

/**
 * TrackSearch represents the model behind the search form of `app\modules\track\models\Track`.
 */
class TrackSearch extends Track
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            [['id', 'track_number', 'status', 'createdAt', 'updatedAt'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search(array $params = []): ActiveDataProvider
    {
        $query = Track::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'track_number', $this->track_number])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
