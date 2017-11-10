<?php


namespace common\collects\content;


use common\collects\BaseCollection;
use common\models\helpers\ModelHelper;
use common\collects\MetaCollection;
use common\models\content\Page;

class PageCollection extends BaseCollection
{
    public $name;
    public $title;
    public $slug;
    public $content;
    public $sort;
    public $status;

    private $_parent;
    private $_page;

    public function __construct(Page $parent = null, Page $page = null, array $config = [])
    {
        if ($page) {
            $this->name = $page->name;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
            $this->sort = $page->sort;
            $this->status = $page->status;

            $this->meta = new MetaCollection($page->meta);
        } else {
            $this->sort = ModelHelper::DEFAULT_SORT;
            $this->status = ModelHelper::STATUS_ACTIVE;

            $this->meta = new MetaCollection();
        }

        $this->_parent = $parent;
        $this->_page = $page;

        parent::__construct($config);
    }

    public function getParent()
    {
        return $this->_parent;
    }

    public function getPage()
    {
        return $this->_page;
    }

    public function internalCollections()
    {
        return ['meta'];
    }

    public function rules()
    {
        return [
            [['name', 'title', 'slug', 'sort'], 'required'],
            [['content'], 'string'],
            [['sort'], 'integer'],
            ['status', 'boolean'],
            [['name', 'title', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique', 'targetClass' => Page::class, 'filter' => $this->_page ? ['<>', 'id', $this->_page->id] : null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'    => 'Название',
            'title'   => 'Заголовок',
            'slug'    => 'Символьный код',
            'content' => 'Контент',
            'sort'    => 'Сортировка',
            'status'  => 'Опубликовано',
        ];
    }
}