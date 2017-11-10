<?php

namespace common\models\catalog\import;

use Yii;

/**
 * This is the model class for table "{{%import_profile_sections}}".
 *
 * @property integer $profile_id
 * @property integer $value
 *
 * @property ImportProfile $profile
 */
class ImportProfileSections extends \yii\db\ActiveRecord
{
    /**
     * @param $value
     *
     * @return ImportProfileSections
     */
    public static function create($value)
    {
        return new static([
            'value'      => $value
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%import_profile_sections}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile ID',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(ImportProfile::className(), ['id' => 'profile_id']);
    }
}
