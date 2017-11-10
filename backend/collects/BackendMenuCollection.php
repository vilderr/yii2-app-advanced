<?php

namespace backend\collects;

use yii\base\Model;
use backend\models\BackendMenu;
use common\models\helpers\ModelHelper;

/**
 * Class MenuCollection
 * @package backend\collects\menu
 */
class BackendMenuCollection extends Model
{
    const DEFAULT_ICON = 'chevron-right';

    public $name;
    public $route;
    public $icon;
    public $sort;
    public $parent_id;

    private $_parent;
    private $_item;

    public function __construct(BackendMenu $parent = null, BackendMenu $item = null, array $config = [])
    {
        if ($item) {
            $this->name = $item->name;
            $this->route = $item->route;
            $this->icon = $item->icon;
            $this->sort = $item->sort;
        } else {
            $this->icon = self::DEFAULT_ICON;
            $this->sort = ModelHelper::DEFAULT_SORT;
        }

        $this->_parent = $parent;
        $this->parent_id = $parent ? $parent->id : 0;
        $this->_item = $item;

        parent::__construct($config);
    }

    public function isRoot()
    {
        return $this->_parent ? false : true;
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sort'], 'integer'],
            [['name', 'route', 'icon'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'  => 'Название',
            'route' => 'Путь',
            'icon'  => 'Иконка',
            'sort'  => 'Сортировка',
        ];
    }
}