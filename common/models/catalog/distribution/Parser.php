<?php

namespace common\models\catalog\distribution;

use yii\helpers\Json;
use common\managers\catalog\ProductManager;
use common\models\catalog\product\Product;
use common\models\catalog\distribution\DistributionProfile as Profile;
use common\models\catalog\distribution\DistributionProfilePart as Part;
use common\collects\catalog\ProductCollection;
use common\models\helpers\ModelHelper;

/**
 * Class Parser
 * @package common\models\catalog\distribution
 */
class Parser
{
    /** @var  Profile */
    public $model;

    /** @var  array */
    private $_ns;
    /**
     * @var Part[]
     */
    private $_parts = [];
    private $_productManager;

    public function __construct(ProductManager $productManager, &$ns)
    {
        $this->_productManager = $productManager;
        $this->_ns = &$ns;

        $this->model = Profile::findOne($this->_ns['id']);
        $this->_parts = $this->model->getParts()->andWhere(['status' => ModelHelper::STATUS_ACTIVE])->orderBy('sort')->all();
        $this->_ns['count'] = count($this->_parts);
    }

    public function parseElements($key, $start_time, $interval = 30)
    {
        $counter = [
            'upd' => 0,
            'err' => 0,
            'crc' => 0,
        ];

        $part = $this->_parts[$key];
        $filter = Json::decode($part->filter_json);
        $actions = Json::decode($part->action_json);

        $query = Product::find()
            ->limit(1000)
            ->where(['>', 'id', $this->_ns['last_id']]);

        foreach ($filter as $name => $value) {
            switch ($name) {
                case 'filter_name':
                    $query->andFilterWhere(['like', 'name', $value]);
                    break;
                case 'filter_category_id':
                    if ($value > 0) {
                        $query->andFilterWhere(['category_id' => $value]);
                    }
                    break;
                case 'filter_status':
                    if ($value != '') {
                        $query->andFilterWhere(['status' => $value]);
                    }
                    break;
                case 'filter_price_from':
                    $query->andFilterWhere(['>=', 'price', $value]);
                    break;
                case 'filter_price_to':
                    $query->andFilterWhere(['<', 'price', $value]);
                    break;
            }
        }

        $query->orderBy(['id' => SORT_ASC]);

        foreach ($query->each(100) as $product) {
            $this->updateProduct($counter, $product, $actions);
            $counter['crc']++;
            $this->_ns['last_id'] = $product->id;
            if ($interval > 0 && (time() - $start_time) > $interval)
                break;
        }

        return $counter;
    }

    private function updateProduct(&$counter, $product, $operations)
    {
        $collection = new ProductCollection($product);
        foreach ($operations as $name => $value) {
            switch ($name) {
                case 'action_category_id':
                    if ($value > 0) {
                        $collection->category_id = $value;
                    }
                    break;
                case 'action_status':
                    if ($value != '') {
                        $collection->status = $value;
                    }
                    break;
                case 'action_tags':
                    if ($value != '')
                        $collection->tags->values = $value;
                    break;
            }
        }

        try {
            $this->_productManager->edit($product, $collection);
            $counter['upd']++;
        } catch (\DomainException $e) {
            $counter['err']++;
        }
    }
}