<?php

namespace common\collects;

use yii\base\Model;
use common\models\Meta;

class MetaCollection extends Model
{
    public $title;
    public $description;
    public $keywords;

    public function __construct(Meta $meta = null, array $config = [])
    {
        if ($meta) {
            $this->title = $meta->title;
            $this->description = $meta->description;
            $this->keywords = $meta->keywords;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['description', 'keywords'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title'               => 'Заголовок страницы',
            'description'         => 'Описание страницы',
            'keywords'            => 'Ключевые слова'
        ];
    }
}