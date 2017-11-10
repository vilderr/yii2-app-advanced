<?php

namespace common\models\catalog\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\Event;
use yii\db\Query;
use common\models\catalog\category\Category;
use common\models\helpers\CategoryHelper;
use common\models\helpers\ModelHelper;

/**
 * Class CategoryRecalcBehavior
 * @package common\models\catalog\behaviors
 */
class CategoryRecalcBehavior extends Behavior
{
    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE  => 'onAfterUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'onBeforeDelete'
        ];
    }

    /**
     * @param Event $event
     */
    public function onAfterUpdate(Event $event)
    {
        /** @var Category $category */
        $category = $event->sender;

        $this->recalcGlobalActive($category);
    }

    public function onBeforeDelete(Event $event)
    {
        /** @var Category $category */
        $category = $event->sender;

        if ($category->picture)
            $category->picture->delete();
    }

    /**
     * Пересчет глобальной активности категории и ее вложенных подкатегорий
     *
     * @param Category $category
     */
    private function recalcGlobalActive(Category $category)
    {
        Yii::$app->db->createCommand()->update(Category::tableName(), ['global_status' => ModelHelper::STATUS_ACTIVE], 'lft >= :lft AND rgt <= :rgt', [':lft' => $category->lft, ':rgt' => $category->rgt])->execute();

        $q = new Query();
        $q
            ->select([
                'lft',
                'rgt',
            ])
            ->from(Category::tableName())
            ->andWhere('lft >= :lft', [':lft' => $category->lft])
            ->andWhere('rgt <= :rgt', [':rgt' => $category->rgt])
            ->andWhere(['status' => ModelHelper::STATUS_WAIT])
            ->orderBy('lft');

        $upd = [];
        $prev_rgt = 0;
        foreach ($q->each() as $child) {
            /** @var Category $child */
            if ($child['rgt'] > $prev_rgt) {
                $prev_rgt = $child['rgt'];
                $upd[] = '(lft >= ' . $child['lft'] . ' AND rgt <= ' . $child['rgt'] . ')';
            }
        }

        if (count($upd) > 0) {
            Yii::$app->db->createCommand()->update(Category::tableName(), ['global_status' => CategoryHelper::STATUS_WAIT], implode(' OR ', $upd))->execute();
        }
    }
}