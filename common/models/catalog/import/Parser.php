<?php

namespace common\models\catalog\import;

use common\dispatchers\DeferredEventDispatcher;
use common\models\catalog\product\events\PictureAssigned;
use common\models\helpers\ModelHelper;
use common\dispatchers\EventDispatcher;
use Yii;
use yii\helpers\Json;
use common\managers\catalog\PropertyManager;
use common\managers\catalog\PropertyValueManager;
use common\managers\catalog\ProductManager;
use common\models\helpers\ImportProfileHelper;
use common\models\catalog\import\ImportProfileTree as Tree;
use common\models\catalog\product\Product;
use common\models\catalog\property\Property;
use common\models\catalog\property\PropertyValue;
use common\collects\catalog\ProductCollection;
use common\collects\catalog\property\PropertyCollection;
use common\collects\catalog\property\PropertyValueCollection;
use common\helpers\StringHelper;

/**
 * Class Parser
 * @package common\models\catalog\import
 */
class Parser
{
    private $_ns;

    private $_productManager;
    private $_propertyManager;
    private $_propertyValueManager;
    private $_counter = 0;
    private $_translitParams = ['replace_space' => '_', 'replace_other' => '_'];

    public function __construct
    (
        ProductManager $productManager,
        PropertyManager $propertyManager,
        PropertyValueManager $propertyValueManager,
        &$ns
    )
    {
        $this->_productManager = $productManager;
        $this->_propertyManager = $propertyManager;
        $this->_propertyValueManager = $propertyValueManager;
        $this->_ns = &$ns;
    }

    public function importElements($startTime, $interval)
    {
        $counter = [
            'crc' => 0,
            'add' => 0,
            'upd' => 0,
            'err' => 0,
        ];

        $r = ImportProfileHelper::sendRestRequest($this->_ns['url'], $this->_ns['token'], ['last_id' => $this->_ns['last_id'], 'limit' => 50, 'sections' => Json::encode($this->_ns['sections'])]);
        $result = Json::decode($r);

        if (is_array($result)) {
            foreach ($result as $item) {
                $counter['crc']++;

                if ($ID = $this->importElement($item, $counter)) {
                    $this->addToTree($ID);
                }

                $this->_ns['last_id'] = $item['id'];

                if ($interval > 0 && (time() - $startTime) > $interval)
                    break;
            }
        }

        return $counter;
    }

    private function importElement($item, &$counter)
    {
        $result = false;
        $arProduct = [];

        $bMatch = false;
        $xml_id = $item['xml_id'];
        $tmp_id = $this->getArrayCrc($item);

        if ($product = Product::find()->where(['xml_id' => $xml_id])->one()) {
            $bMatch = ($tmp_id == $product->tmp_id);
        }

        if ($bMatch) {
            $counter['upd']++;

            return $product->id;
        }

        $collection = $product ? new ProductCollection($product) : new  ProductCollection();
        $arProduct['ProductCollection'] = [
            'name'            => $item['name'],
            'xml_id'          => $xml_id,
            'tmp_id'          => $tmp_id,
            'current_section' => $item['current_section'],
            'url'             => $item['url'],
            'price'           => $item['price'],
            'old_price'       => $item['old_price'],
            'discount'        => $item['discount'],
        ];

        if (!$product || !$product->picture) {
            $arProduct['ProductCollection']['remote_picture_url'] = $item['picture'];
        }

        if ($item['brand']) {
            $property = $this->checkProperty('brand');

            $value = $this->checkPropertyValue($property, $item['brand']);
            $arProduct['ValuesCollection'][$property->slug]['values'][] = $value->id;
        }

        foreach ($item['properties'] as $name => $values) {
            $property = $this->checkProperty($name);
            $arValues = [];

            foreach ($values as $val) {
                $arValues[] = $this->checkPropertyValue($property, $val)->id;
            }

            $arProduct['ValuesCollection'][$property->slug]['values'] = $arValues;
        }

        $arProduct['TagsCollection'] = $collection->tags;
        $arProduct['PictureCollection'] = [];

        try {
            if ($collection->load($arProduct)) {
                if ($product) {
                    $this->_productManager->edit($product, $collection);
                    $counter['upd']++;
                } else {
                    $product = $this->_productManager->create($collection);
                    $counter['add']++;
                }

                $result = $product->id;
            } else {
                $counter['err']++;
            }
        } catch (\DomainException $e) {
            $counter['err']++;
            Yii::$app->errorHandler->logException($e);
        }

        return $result;
    }

    private function checkProperty($name)
    {

        if (null === ($property = Property::findOne(['xml_id' => $name]))) {
            $collection = new PropertyCollection(null, [
                'name'   => $name,
                'slug'   => $name,
                'xml_id' => $name,
            ]);

            $property = $this->_propertyManager->create($collection);
        }

        return $property;
    }

    private function checkPropertyValue(Property $property, $name)
    {
        $xml_id = StringHelper::translit($name, 'ru', $this->_translitParams) . '_' . $property->xml_id;

        if (null === ($value = PropertyValue::findOne(['xml_id' => $xml_id]))) {
            $collection = new PropertyValueCollection($property, null, [
                'property_id' => $property->id,
                'name'        => $name,
                'slug'        => StringHelper::translit($name, 'ru', $this->_translitParams),
                'xml_id'      => $xml_id,
            ]);

            $value = $this->_propertyValueManager->create($collection);
        }

        return $value;
    }

    protected function getArrayCrc(array $array)
    {
        $c = crc32(print_r($array, true));
        if ($c > 0x7FFFFFFF)
            $c = -(0xFFFFFFFF - $c + 1);

        return $c;
    }

    protected function addToTree($ID)
    {
        $treeItem = new Tree([
            'element_id' => $ID,
            'profile_id' => $this->_ns['profile_id'],
        ]);

        $treeItem->save(false);
    }
}