<?php

namespace common\repo\content;

use yii\caching\Cache;
use yii\web\NotFoundHttpException;
use common\models\content\Page;

/**
 * Class PageRepository
 * @package common\repo\content
 */
class PageRepository
{
    private $_cache;

    public function __construct(Cache $cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @param $id
     *
     * @return Page
     */
    public function get($id)
    {
        if (!$page = Page::findOne($id)) {
            throw new \DomainException('Элемент не найден');
        }

        return $page;
    }

    /**
     * @param $id
     *
     * @return null|Page
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($page = Page::findOne($id)) !== null) {
            return $page;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    /**
     * @param Page $page
     */
    public function save(Page $page)
    {
        if (!$page->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Page $page
     */
    public function delete(Page $page)
    {
        if (!$page->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}