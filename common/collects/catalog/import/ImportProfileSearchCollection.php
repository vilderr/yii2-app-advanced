<?php

namespace common\collects\catalog\import;

use yii\data\ActiveDataProvider;
use common\models\catalog\import\ImportProfile as Profile;

/**
 * Class ImportProfileSearchCollection
 * @package common\collects\catalog\import
 */
class ImportProfileSearchCollection extends Profile
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'sections_url', 'products_url', 'api_key'], 'safe'],
            [['id', 'sort', 'status'], 'integer'],
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Profile::find();

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
            ->andFilterWhere(['like', 'sections_url', $this->sections_url])
            ->andFilterWhere(['like', 'products_url', $this->products_url])
            ->andFilterWhere(['like', 'api_key', $this->api_key]);

        return $dataProvider;
    }
}