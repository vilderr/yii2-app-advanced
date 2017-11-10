<?php

namespace common\collects\content;

use common\collects\BaseCollection;
use common\collects\PictureCollection;
use common\models\content\MainSlider as Slide;
use common\models\helpers\ModelHelper;

/**
 * Class MainSliderCollection
 * @package common\collects\content
 */
class MainSliderCollection extends BaseCollection
{
    public $text;
    public $url;
    public $sort;
    public $status;

    private $_slide;

    public function __construct(Slide $slide = null, array $config = [])
    {
        if ($slide) {
            $this->text = $slide->text;
            $this->url = $slide->url;
            $this->sort = $slide->sort;
            $this->status = $slide->status;
        } else {
            $this->sort = ModelHelper::DEFAULT_SORT;
            $this->status = ModelHelper::STATUS_ACTIVE;
        }

        $this->picture = new PictureCollection();
        $this->_slide = $slide;
        parent::__construct($config);
    }

    public function getSlide()
    {
        return $this->_slide;
    }

    public function internalCollections()
    {
        return ['picture'];
    }

    public function rules()
    {
        return [
            [['text', 'url'], 'required'],
            ['text', 'string'],
            ['url', 'url', 'defaultScheme' => 'http'],
            ['sort', 'integer'],
            ['status', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'text'   => 'Текст',
            'url'    => 'Урл на который ведет ссылка',
            'sort'   => 'Сортировка',
            'status' => 'Активность',
        ];
    }
}