<?php

namespace common\models\catalog\distribution;

use Yii;
use common\collects\catalog\distribution\DistributionProfileCollection as Collection;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use common\models\catalog\distribution\DistributionProfilePart as Part;

/**
 * This is the model class for table "{{%distribution_profiles}}".
 *
 * @property integer                   $id
 * @property string                    $name
 * @property string                    $description
 * @property integer                   $sort
 * @property integer                   $status
 *
 * @property DistributionProfilePart[] $parts
 */
class DistributionProfile extends \yii\db\ActiveRecord
{
    /**
     * @param Collection $collection
     *
     * @return DistributionProfile
     */
    public static function create(Collection $collection)
    {
        return new static([
            'name'        => $collection->name,
            'description' => $collection->description,
            'sort'        => $collection->sort,
            'status'      => $collection->status,
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->name = $collection->name;
        $this->description = $collection->description;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
    }

    public function revokeParts()
    {
        $this->parts = [];
    }

    public function assignPart($id)
    {
        $parts = $this->parts;

        foreach ($parts as $part) {
            if ($part->distribution_id == $id) {
                return;
            }
        }

        $parts[] = Part::create($id);
        $this->sections = $parts;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParts()
    {
        return $this->hasMany(DistributionProfilePart::className(), ['profile_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%distribution_profiles}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'name'        => 'Название',
            'description' => 'Описание',
            'sort'        => Yii::t('app', 'Sort'),
            'status'      => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'relations' => [
                'class'     => SaveRelationsBehavior::className(),
                'relations' => ['parts'],
            ],
        ];
    }
}
