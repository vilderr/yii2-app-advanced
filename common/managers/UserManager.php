<?php


namespace common\managers;

use common\managers\TransactionManager as Transaction;
use common\repo\UserRepository as Users;
use common\managers\RoleManager as Roles;
use common\models\user\User;
use common\collects\user\UserCollection as Collection;


class UserManager
{
    private $_users;
    private $_roles;
    private $_transaction;

    public function __construct(Users $users, Roles $roles, Transaction $transaction)
    {
        $this->_users = $users;
        $this->_roles = $roles;
        $this->_transaction = $transaction;
    }

    public function create(Collection $collection)
    {
        $user = User::create($collection);
        $this->_transaction->wrap(function () use ($user, $collection) {
            $this->_users->save($user);
            $this->_roles->assign($user->id, $collection->roles);
        });

        return $user;
    }

    public function edit(User $user, Collection $collection)
    {
        $user->edit($collection);

        $this->_transaction->wrap(function () use ($user, $collection) {
            $this->_users->save($user);
            $this->_roles->assign($user->id, $collection->roles);
        });
    }
}