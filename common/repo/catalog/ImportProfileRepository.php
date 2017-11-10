<?php

namespace common\repo\catalog;

use yii\web\NotFoundHttpException;
use common\models\catalog\import\ImportProfile as Profile;

/**
 * Class ImportProfileRepository
 * @package common\repo\catalog
 */
class ImportProfileRepository
{
    /**
     * @param $id
     *
     * @return Profile
     */
    public function get($id)
    {
        if (!$profile = Profile::findOne($id)) {
            throw new \DomainException('Профиль не найден');
        }

        return $profile;
    }

    /**
     * @param $id
     *
     * @return Profile
     * @throws NotFoundHttpException
     */
    public function find($id)
    {
        if (($profile = Profile::findOne($id)) !== null) {
            return $profile;
        } else {
            throw new NotFoundHttpException('Страница не найдена');
        }
    }

    /**
     * @param Profile $profile
     */
    public function save(Profile $profile)
    {
        if (!$profile->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Profile $profile
     */
    public function delete(Profile $profile)
    {
        if (!$profile->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}