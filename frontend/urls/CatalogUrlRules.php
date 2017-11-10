<?php

namespace frontend\urls;

use yii\caching\Cache;
use yii\helpers\ArrayHelper;
use yii\base\BaseObject;
use yii\web\UrlRuleInterface;
use yii\caching\TagDependency;
use yii\base\InvalidParamException;
use common\models\catalog\category\Category;
use common\repo\CategoryRepository as Repository;
use DevGroup\TagDependencyHelper\NamingHelper;

/**
 * Class CatalogUrlRules
 * @package frontend\urls
 */
class CatalogUrlRules extends BaseObject implements UrlRuleInterface
{
    public $prefix = 'catalog';

    private $_repository;
    private $_cache;

    public function __construct(Repository $repository, Cache $cache, array $config = [])
    {
        parent::__construct($config);
        $this->_repository = $repository;
        $this->_cache = $cache;
    }

    public function parseRequest($manager, $request)
    {
        if (preg_match('#^' . $this->prefix . '/#', $request->pathInfo)) {
            $chunks = explode('/', trim($request->pathInfo, '/'));
            ArrayHelper::remove($chunks, 0);
            $allCategory = ArrayHelper::getColumn($this->_repository->getList(null, false, true), 'slug');

            $path = [];
            foreach ($chunks as $i => $chunk) {
                if (ArrayHelper::isIn($chunk, $allCategory)) {
                    $path[] = ArrayHelper::remove($chunks, $i);
                    continue;
                }
                break;
            }

            $slug_path = implode('/', $path);
            if (false === ($tail = $this->getTail($chunks))) {
                return false;
            }

            $result = $this->_cache->getOrSet('Category:' . $slug_path, function () use ($slug_path) {
                if (!$category = $this->_repository->findBySlugPath($slug_path)) {
                    return ['id' => null, 'path' => null];
                }

                return ['id' => $category->id, 'path' => $category->slug_path];
            }, null, new TagDependency([
                'tags' => [
                    NamingHelper::getCompositeTag(Category::class, ['slug_path' => $slug_path]),
                ],
            ]));

            if ($result['id'] === null || $slug_path != $result['path']) {
                return false;
            }

            //\Yii::info(print_r($tail, 1), 'info');
            return ['catalog/category', ['id' => $result['id'], 'sef' => $tail]];
        }

        return false;
    }

    public function createUrl($manager, $route, $params)
    {
        if ($route == 'catalog/index') {
            $url = $this->prefix . '/';
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }

            return $url;
        } else if ($route == 'catalog/product') {
            if (empty($params['id'])) {
                throw new InvalidParamException('Empty id.');
            }

            $id = ArrayHelper::remove($params, 'id');
            $url = $this->prefix . '/product/' . $id . '/';
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $url .= '?' . $query;
            }

            return $url;
        } else if ($route == 'catalog/category') {
            if (empty($params['id'])) {
                throw new InvalidParamException('Empty id.');
            }
            $id = ArrayHelper::remove($params, 'id');
            $sef = ArrayHelper::remove($params, 'sef', []);
            $query = ArrayHelper::remove($params, 'query', []);

            $url = $this->_cache->getOrSet('Category:' . $id, function () use ($id, $sef, $query) {
                if (!$category = $this->_repository->find($id)) {
                    return null;
                }

                $url = $this->prefix . '/';
                if ($category->id > 1)
                    $url .= $category->slug_path.'/';

                return $url;
            }, null, new TagDependency([
                'tags' => [
                    NamingHelper::getObjectTag(Category::class, $id),
                ],
            ]));

            if (!$url) {
                throw new InvalidParamException('Undefined id.');
            }

            if (!empty($sef)) {
                foreach ($sef as $k => $v) {
                    $url .= $k . '-' . $v . '/';
                }
            }

            if (!empty($query)) {
                $arQueryStr = [];

                foreach ($query as $k => $v) {
                    $arQueryStr[] = $k . '=' . $v;
                }

                $url .= '?' . implode('&', $arQueryStr);
            }

            if (!empty($params)) {
                $arParamsStr = [];

                foreach ($params as $k => $v) {
                    $arParamsStr[] = $k . '=' . $v;
                }

                $url .= empty($query) ? '?' : '&';
                $url .= implode('&', $arParamsStr);
            }

            return $url;
        }

        return false;
    }

    private function getTail($chunks)
    {
        $tail = [];
        foreach ($chunks as $chunk) {
            if (count($a = explode('-', $chunk)) <> 2) {
                return false;
            }

            $tail[$a[0]] = $a[1];
        }

        return $tail;
    }
}