<?php

namespace common\repo;

use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\Tag;
use yii\caching\TagDependency;
use DevGroup\TagDependencyHelper\NamingHelper;

/**
 * Class TagRepository
 * @package common\repo
 */
class TagRepository
{
    /**
     * @param $id
     *
     * @return Tag
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($tag = Tag::findOne($id)) !== null) {
            return $tag;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    /**
     * @param Tag $tag
     */
    public function save(Tag $tag)
    {
        if (!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Tag $tag
     */
    public function delete(Tag $tag)
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public static function getDropDownList()
    {
        $result = \Yii::$app->cache->getOrSet(
            'Tags:dropdownList',
            function () {
                return Tag::find()->orderBy('name')->all();
            },
            null,
            new TagDependency([
                'tags' => NamingHelper::getCommonTag(Tag::class),
            ])
        );

        return ArrayHelper::map($result, 'id', 'name');
    }
}