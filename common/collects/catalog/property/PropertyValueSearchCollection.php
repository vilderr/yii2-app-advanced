<?php

namespace common\collects\catalog\property;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\property\PropertyValue;

/**
 * Class PropertyValueSearchCollection
 * @package common\collects\catalog\property
 */
class PropertyValueSearchCollection extends Model
{
    public $id;
    public $property_id;
    public $name;
    public $slug;
    public $xml_id;
    public $sort;
    public $status;

    public $property;

    public function rules()
    {
        return [
            [['id', 'property_id', 'sort', 'status'], 'integer'],
            [['name', 'slug', 'xml_id'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = PropertyValue::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'          => $this->id,
            'property_id' => $this->property->id,
            'sort'        => $this->sort,
            'status'      => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'xml_id', $this->xml_id]);

        return $dataProvider;
    }
}