<?php

namespace common\collects\catalog;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\catalog\product\Product;

/**
 * Class ProductSearchCollection
 * @package backend\collects\catalog
 */
class ProductSearchCollection extends Model
{
    public $id;
    public $name;
    public $xml_id;
    public $category_id;
    public $created_date_from;
    public $created_date_to;
    public $updated_date_from;
    public $updated_date_to;
    public $current_section;
    public $price_from;
    public $price_to;
    public $old_price_from;
    public $old_price_to;
    public $discount_from;
    public $discount_to;
    public $sort;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'price_from', 'price_to', 'old_price_from', 'old_price_to', 'discount_from', 'discount_to', 'sort', 'status'], 'integer'],
            [['name', 'xml_id', 'description', 'current_props', 'current_section'], 'safe'],
            [['created_date_from', 'created_date_to', 'updated_date_from', 'updated_date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function search($params)
    {
        $query = Product::find();

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
            'category_id' => $this->category_id,
            'sort'        => $this->sort,
            'status'      => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'xml_id', $this->xml_id])
            ->andFilterWhere(['like', 'current_section', $this->current_section])
            ->andFilterWhere(['>=', 'created_at', $this->created_date_from ? strtotime($this->created_date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->created_date_to ? strtotime($this->created_date_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'updated_at', $this->updated_date_from ? strtotime($this->updated_date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'updated_at', $this->updated_date_to ? strtotime($this->updated_date_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'price', $this->price_from ? $this->price_from : null])
            ->andFilterWhere(['<=', 'price', $this->price_to ? $this->price_to : null])
            ->andFilterWhere(['>=', 'old_price', $this->old_price_from ? $this->old_price_from : null])
            ->andFilterWhere(['<=', 'old_price', $this->old_price_to ? $this->old_price_to : null])
            ->andFilterWhere(['>=', 'discount', $this->discount_from ? $this->discount_from : null])
            ->andFilterWhere(['<=', 'discount', $this->discount_to ? $this->discount_to : null]);

        return $dataProvider;
    }
}