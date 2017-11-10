<?php

namespace common\repo;

use common\dispatchers\EventDispatcher;
use common\models\helpers\UserHelper;
use common\models\user\User;

/**
 * Class UserRepository
 * @package common\repo
 */
class UserRepository
{
    private $_dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
    }

    public function save(User $user)
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
        $this->_dispatcher->dispatchAll($user->releaseEvents());
    }

    public static function findActiveById($id)
    {
        return User::findOne(['id' => $id, 'status' => UserHelper::STATUS_ACTIVE]);
    }

    /**
     * @param $value
     *
     * @return null|User
     */
    public function findByUsernameOrEmail($value)
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }
}