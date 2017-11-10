<?php

namespace common\repo\catalog;

use yii\web\NotFoundHttpException;
use common\models\catalog\distribution\DistributionProfilePart as Part;

/**
 * Class DistributionProfilePartRepository
 * @package common\repo\catalog
 */
class DistributionProfilePartRepository
{
    /**
     * @param $id
     *
     * @return Part
     */
    public function get($id)
    {
        if (!$part = Part::findOne($id)) {
            throw new \DomainException('Итерация не найдена');
        }

        return $part;
    }

    /**
     * @param $id
     *
     * @return Part
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($part = Part::findOne($id)) !== null) {
            return $part;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    /**
     * @param Part $part
     */
    public function save(Part $part)
    {
        if (!$part->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Part $part
     */
    public function delete(Part $part)
    {
        if (!$part->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}