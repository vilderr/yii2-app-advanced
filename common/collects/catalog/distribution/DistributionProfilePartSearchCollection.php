<?php

namespace common\collects\catalog\distribution;

use yii\data\ActiveDataProvider;
use common\models\catalog\distribution\DistributionProfilePart as Part;

/**
 * Class DistributionProfilePartSearchCollection
 * @package common\collects\catalog\distribution
 */
class DistributionProfilePartSearchCollection extends Part
{
    public function rules()
    {
        return [
            ['name', 'safe'],
            [['id', 'profile_id'], 'integer'],
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
            'id'         => $this->id,
            'profile_id' => $this->profile_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}