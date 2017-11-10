<?php

namespace common\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;
use yii\caching\TagDependency;
use yii\web\IdentityInterface;
use common\models\AggregateRoot;
use common\models\EventTrait;
use common\collects\user\UserCollection as Collection;
use common\models\helpers\UserHelper;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\NamingHelper;
use DevGroup\TagDependencyHelper\TagDependencyTrait;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property integer $id
 * @property string  $username
 * @property string  $name
 * @property string  $last_name
 * @property string  $email
 * @property string  $phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string  $auth_key
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $email_confirm_token
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface, AggregateRoot
{
    use EventTrait;
    use TagDependencyTrait;

    public static function create(Collection $collection)
    {
        $user = new static();
        $user->username = $collection->username;
        $user->name = $collection->name;
        $user->last_name = $collection->last_name;
        $user->email = $collection->email;
        $user->phone = $collection->phone;
        $user->setPassword(!empty($collection->password) ? $collection->password : Yii::$app->security->generateRandomString());
        $user->status = $collection->status;
        $user->auth_key = Yii::$app->security->generateRandomString();

        return $user;
    }

    public function edit(Collection $collection)
    {
        $this->username = $collection->username;
        $this->name = $collection->name;
        $this->last_name = $collection->last_name;
        $this->email = $collection->email;
        $this->phone = $collection->phone;
        $this->status = $collection->status;

        if (!empty($collection->password)) {
            $this->setPassword($collection->password);
        }
    }

    public static function findIdentity($id)
    {
        return Yii::$app->cache->getOrSet(['user_identity', 'id' => $id], function () use ($id) {
            return self::findOne($id);
        }, null, new TagDependency(['tags' => NamingHelper::getObjectTag(self::class, $id)]));
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function isWait()
    {
        return $this->status === UserHelper::STATUS_WAIT;
    }

    public function isActive()
    {
        return $this->status === UserHelper::STATUS_ACTIVE;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ],
            'cacheable' => [
                'class' => CacheableActiveRecord::class,
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => 'ID',
            'username'             => 'Username',
            'name'                 => 'Name',
            'last_name'            => 'Last Name',
            'email'                => 'Email',
            'phone'                => 'Phone',
            'created_at'           => 'Created At',
            'updated_at'           => 'Updated At',
            'status'               => 'Status',
            'auth_key'             => 'Auth Key',
            'password_hash'        => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email_confirm_token'  => 'Email Confirm Token',
        ];
    }
}
