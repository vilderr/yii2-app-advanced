<?php

namespace common\collects\user;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\user\User;
use common\collects\validators\Password;
use common\collects\validators\Username;
use common\collects\validators\Phone;
use common\models\helpers\UserHelper;

/**
 * Class UserCollection
 * @package common\collects\user
 */
class UserCollection extends Model
{
    const SCENARIO_REGISTER = 'register';

    public $username;
    public $name;
    public $last_name;
    public $email;
    public $phone;
    public $password;
    public $status;
    public $roles;
    public $password_repeat;

    private $_user;

    public function __construct(User $user = null, array $config = [])
    {
        parent::__construct($config);

        if ($user) {
            $this->username = $user->username;
            $this->name = $user->name;
            $this->last_name = $user->last_name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->status = $user->status;
            $this->roles = ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($user->id), 'name');
        } else {
            $this->status = UserHelper::STATUS_ACTIVE;
            $this->scenario = self::SCENARIO_REGISTER;
        }

        $this->_user = $user;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'email', 'phone', 'roles'], 'required'],
            [['username', 'name', 'last_name'], 'string', 'max' => 255],
            [['username', 'email', 'phone'], 'unique', 'targetClass' => User::class, 'filter' => $this->_user ? ['<>', 'id', $this->_user->id] : null],
            [['password', 'password_repeat'], Password::class],
            ['username', Username::class],
            ['email', 'email'],
            ['phone', Phone::class],
            ['password', 'string', 'min' => 6],
            [['password'], 'required', 'on' => self::SCENARIO_REGISTER],
            ['password', 'compare'],
            ['status', 'boolean'],
            ['roles', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'        => 'Логин',
            'name'            => 'Имя',
            'last_name'       => 'Фамилия',
            'email'           => 'Email',
            'phone'           => 'Телефон',
            'password'        => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'status'          => 'Активность',
            'roles'           => 'Группы пользователя',
        ];
    }

    public function rolesList()
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}