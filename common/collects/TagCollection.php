<?php

namespace common\collects;

use yii\base\Model;
use common\models\Tag;
use common\collects\validators\SlugValidator;

/**
 * Class TagCollection
 * @package common\collects
 */
class TagCollection extends Model
{
    public $name;
    public $slug;

    private $_tag;

    public function __construct(Tag $tag = null, array $config = [])
    {
        if ($tag) {
            $this->name = $tag->name;
            $this->slug = $tag->slug;
        }

        $this->_tag = $tag;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', SlugValidator::class],
            [['slug'], 'unique', 'targetClass' => Tag::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null],
        ];
    }

    public function getTag()
    {
        return $this->_tag;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'slug' => 'Символьный код',
        ];
    }
}