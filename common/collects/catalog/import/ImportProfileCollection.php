<?php

namespace common\collects\catalog\import;

use common\collects\BaseCollection;
use common\models\catalog\import\ImportProfile as Profile;
use common\models\helpers\ModelHelper;
use common\collects\catalog\import\ImportProfileSectionsCollection as SectionsCollection;

/**
 * Class ImportProfileCollection
 * @package common\collects\catalog\import
 */
class ImportProfileCollection extends BaseCollection
{
    public $name;
    public $sections_url;
    public $products_url;
    public $api_key;
    public $sort;
    public $status;

    private $_profile;

    public function __construct(Profile $profile = null, array $config = [])
    {
        if ($profile) {
            $this->name = $profile->name;
            $this->sections_url = $profile->sections_url;
            $this->products_url = $profile->products_url;
            $this->api_key = $profile->api_key;
            $this->sort = $profile->sort;
            $this->status = $profile->status;

            $this->sections = new SectionsCollection($profile);
        } else {
            $this->sections_url = 'http://baseproducts.ru/rest/import/sections';
            $this->products_url = 'http://baseproducts.ru/rest/import/products';
            $this->sort = ModelHelper::DEFAULT_SORT;
            $this->status = ModelHelper::STATUS_ACTIVE;
        }

        $this->_profile = $profile;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'sections_url', 'products_url', 'api_key'], 'required'],
            [['sort'], 'integer'],
            [['sort'], 'default', 'value' => ModelHelper::DEFAULT_SORT],
            [['status'], 'boolean'],
            [['name', 'sections_url', 'products_url', 'api_key'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'         => 'Название',
            'sections_url' => 'Урл REST сервиса для категорий',
            'products_url' => 'Урл REST сервиса для товаров',
            'api_key'      => 'Ключ доступа',
            'sort'         => 'Сортировка',
            'status'       => 'Активность',
        ];
    }

    public function internalCollections()
    {
        return ['sections'];
    }

    public function getProfile()
    {
        return $this->_profile;
    }
}