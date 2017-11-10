<?php

namespace common\collects\user;

use yii\base\Model;

/**
 * Class LoginCollection
 * @package common\collects\user
 */
class LoginCollection extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'   => 'Логин или email',
            'password'   => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }
}