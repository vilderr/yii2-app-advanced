<?php

namespace common\models\content;

use Yii;
use yii\web\UploadedFile;
use common\models\content\MainSliderPicture as Picture;
use common\collects\content\MainSliderCollection as Collection;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * This is the model class for table "{{%main_slider}}".
 *
 * @property integer $id
 * @property integer $picture_id
 * @property string  $text
 * @property string  $url
 * @property integer $sort
 * @property integer $status
 */
class MainSlider extends \yii\db\ActiveRecord
{
    use TagDependencyTrait;

    public static function create(Collection $collection)
    {
        return new static([
            'text'   => $collection->text,
            'url'    => $collection->url,
            'sort'   => $collection->sort,
            'status' => $collection->status,
        ]);
    }

    public function edit(Collection $collection)
    {
        $this->text = $collection->text;
        $this->url = $collection->url;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
    }

    public function addPicture(UploadedFile $picture)
    {
        $this->picture = Picture::create($picture, 'main_slider');
    }

    public function getPicture()
    {
        return $this->hasOne(Picture::className(), ['id' => 'picture_id'])->andWhere(['model_name' => 'main_slider']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%main_slider}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'text'   => 'Текст',
            'url'    => 'Урл',
            'sort'   => 'Сортировка',
            'status' => 'Активность',
        ];
    }

    public function behaviors()
    {
        return [
            'cacheable' => CacheableActiveRecord::class,
            'relations' => [
                'class'     => SaveRelationsBehavior::class,
                'relations' => [
                    'picture',
                ],
            ],
        ];
    }
}
