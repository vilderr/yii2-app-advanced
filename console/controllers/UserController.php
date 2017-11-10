<?php

namespace console\controllers;

use yii\console\Controller;
use common\collects\user\UserCollection as Collection;
use common\managers\UserManager as Manager;

class UserController extends Controller
{
    private $_manager;

    public function __construct($id, $module, Manager $manager, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->_manager = $manager;
    }

    public function actionCreate()
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $email = $this->prompt('Email:', ['required' => true]);
        $phone = $this->prompt('Phone:', ['required' => true]);
        $password = $this->prompt('Password:', ['required' => true]);
        $password2 = $this->prompt('Retype password:', ['required' => true]);

        if ($password === $password2) {
            $collection = new Collection(null, [
                'username' => $username,
                'email'    => $email,
                'phone'    => $phone,
                'password' => $password,
                'roles'     => ['admin'],
            ]);

            $this->_manager->create($collection);

            $this->stdout('Done!' . PHP_EOL);
        }
    }
}