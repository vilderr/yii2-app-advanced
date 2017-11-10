<?php

namespace backend\repo;

use backend\models\BackendMenu;
use DevGroup\TagDependencyHelper\NamingHelper;
use yii\base\Configurable;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\caching\Cache;

/**
 * Class MenuRepository
 * @package backend\repo
 */
class BackendMenuRepository
{
    /**
     * @param $id
     *
     * @return BackendMenu
     */
    public function get($id)
    {
        if (!$item = BackendMenu::findOne($id)) {
            throw new \DomainException('Пункт меню  не найден');
        }

        return $item;
    }

    /**
     * @param $id
     *
     * @return BackendMenu
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($item = BackendMenu::findOne($id)) !== null) {
            return $item;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    /**
     * @param BackendMenu $item
     */
    public function save(BackendMenu $item)
    {
        if (!$item->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param BackendMenu|null $item
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDescendants(BackendMenu $item = null)
    {
        if ($item) {
            $rows = $item->getDescendants(1);
        } else {
            $rows = BackendMenu::find()->roots();
        }

        return $rows;
    }

    public static function getTree()
    {
        $result = \Yii::$app->cache->getOrSet(
            'BackendMenuTree',
            function () {
                $tree = [];
                $roots = BackendMenu::find()->roots()->orderBy('sort');
                /** @var BackendMenu $root */
                foreach ($roots->each() as $root) {
                    foreach ($root->getDescendants(null, true)->orderBy('lft', 'sort')->asArray()->each() as $item) {
                        $tree[] = $item;
                    }
                }

                return $tree;
            },
            null,
            new TagDependency([
                'tags' => NamingHelper::getCommonTag(BackendMenu::class)
            ])
        );

        return static::rowsToTree($result, 0, 0);
    }

    private static function rowsToTree($rows, $start_index = 0, $current_parent_id = 0)
    {
        $index = $start_index;
        $tree = [];

        while (isset($rows[$index]) === true && $rows[$index]['parent_id'] >= $current_parent_id) {
            if ($rows[$index]['parent_id'] != $current_parent_id) {
                $index++;
                continue;
            }
            $item = $rows[$index];

            $url = isset($item['route']) ? $item['route'] : $item['url'];

            $tree_item = [
                'label' => $item['name'],
                'url'   => preg_match("#^(/|https?://)#Usi", $url) ? $url : ['/' . $url],
            ];
            if (empty($url)) {
                unset($tree_item['url']);
            }

            $attributes_to_check = ['icon', 'class'];
            foreach ($attributes_to_check as $attribute) {
                if (array_key_exists($attribute, $item)) {
                    $tree_item[$attribute] = $item[$attribute];
                }
            }

            $index++;
            $tree_item['items'] = static::rowsToTree($rows, $index, $item['id']);

            $tree[] = $tree_item;
        }

        return $tree;
    }
}