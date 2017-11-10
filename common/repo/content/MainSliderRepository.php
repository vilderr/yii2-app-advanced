<?php

namespace common\repo\content;

use common\models\helpers\ModelHelper;
use DevGroup\TagDependencyHelper\NamingHelper;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\web\NotFoundHttpException;
use common\models\content\MainSlider as Slide;

/**
 * Class MainSliderRepository
 * @package common\repo\content
 */
class MainSliderRepository
{
    private $_cache;

    public function __construct(Cache $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @param $id
     *
     * @return Slide
     */
    public function get($id)
    {
        if (!$slide = Slide::findOne($id)) {
            throw new \DomainException('Элемент не найден');
        }

        return $slide;
    }

    /**
     * @param $id
     *
     * @return null|Slide
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($slide = Slide::findOne($id)) !== null) {
            return $slide;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    public function getList()
    {
        return $this->_cache->getOrSet(['slide_list'], function () {
            return Slide::find()
                ->andWhere(['status' => ModelHelper::STATUS_ACTIVE,])
                ->andWhere(['>', 'picture_id', 0])
                ->with('picture')
                ->orderBy('sort')
                ->all();
        }, 0, new TagDependency(['tags' => NamingHelper::getCommonTag(Slide::class)]));
    }

    /**
     * @param Slide $slide
     */
    public function save(Slide $slide)
    {
        if (!$slide->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Slide $slide
     */
    public function delete(Slide $slide)
    {
        if (!$slide->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}