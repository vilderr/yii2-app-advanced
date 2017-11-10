<?php


namespace common\managers;

use common\models\user\User;
use common\repo\UserRepository as Repository;
use common\collects\user\LoginCollection as Collection;

/**
 * Class AuthManager
 * @package common\managers
 */
class AuthManager
{
    private $_repository;

    public function __construct(Repository $repository)
    {
        $this->_repository = $repository;
    }

    public function auth(Collection $collection): User
    {
        $user = $this->_repository->findByUsernameOrEmail($collection->username);
        if (!$user || !$user->validatePassword($collection->password)) {
            throw new \DomainException('Неверный логин или пароль');
        }

        return $user;
    }
}