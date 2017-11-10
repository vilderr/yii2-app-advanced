<?php

namespace common\collects\catalog\distribution;

use yii\data\ActiveDataProvider;
use common\models\catalog\distribution\DistributionProfile as Profile;

/**
 * Class DistributionProfileSearchCollection
 * @package common\collects\catalog\distribution
 */
class DistributionProfileSearchCollection extends Profile
{
    public function rules()
    {
        return [
            [['name', 'description'], 'safe'],
            [['id', 'sort', 'status'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'     => $this->id,
            'sort'   => $this->sort,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}