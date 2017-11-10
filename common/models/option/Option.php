<?php

namespace common\models\option;

use Yii;

/**
 * This is the model class for table "{{%options}}".
 *
 * @property string $entity
 * @property string $name
 * @property string $value
 */
class Option extends \yii\db\ActiveRecord implements IOptionInterface
{
    /**
     * @param $entity
     * @param $name
     * @param $value
     *
     * @return bool
     */
    public function setOption($entity, $name, $value)
    {
        if ($option = static::findOne(['entity' => $entity, 'name' => $name])) {
            $option->value = $value;
        } else {
            $option = new static([
                'entity' => $entity,
                'name'   => $name,
                'value'  => $value,
            ]);
        }

        return $option->save();
    }

    /**
     * @param $entity
     * @param $name
     *
     * @return bool|false|int
     */
    public function deleteOption($entity, $name)
    {
        if ($option = static::findOne(['entity' => $entity, 'name' => $name])) {
            return $option->delete();
        }

        return true;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $ar = [];
        $options = static::find()->asArray()->all();

        foreach ($options as $o) {
            $ar[$o['entity']][$o['name']] = $o['value'];
        }

        return $ar;
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->option->clearCache();
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->option->clearCache();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%options}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity', 'name', 'value'], 'required'],
            [['value'], 'string'],
            [['entity', 'name'], 'string', 'max' => 255],
            [['entity', 'name'], 'unique', 'targetAttribute' => ['entity', 'name'], 'message' => 'The combination of Entity and Name has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity' => 'Entity',
            'name'   => 'Name',
            'value'  => 'Value',
        ];
    }
}
