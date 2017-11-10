<?php

namespace common\collects\catalog\property;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\property\Property;

/**
 * Class PropertySearchCollection
 * @package common\collects\catalog\property
 */
class PropertySearchCollection extends Model
{
    public $id;
    public $name;
    public $slug;
    public $xml_id;
    public $sort;
    public $status;
    public $filtrable;
    public $sef;

    public function rules()
    {
        return [
            [['name', 'slug', 'xml_id'], 'safe'],
            [['id', 'sort', 'status', 'filtrable', 'sef'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = Property::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'        => $this->id,
            'sort'      => $this->sort,
            'status'    => $this->status,
            'filtrable' => $this->filtrable,
            'sef'       => $this->sef,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'xml_id', $this->xml_id]);

        return $dataProvider;
    }
}