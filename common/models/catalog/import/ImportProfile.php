<?php

namespace common\models\catalog\import;

use Yii;
use common\collects\catalog\import\ImportProfileCollection as Collection;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;

/**
 * This is the model class for table "{{%import_profiles}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $sections_url
 * @property string $products_url
 * @property string $api_key
 * @property integer $sort
 * @property integer $status
 *
 * @property ImportProfileSections[] $sections
 */
class ImportProfile extends \yii\db\ActiveRecord
{
    /**
     * @param Collection $collection
     *
     * @return ImportProfile
     */
    public static function create(Collection $collection)
    {
        return new static([
            'name'         => $collection->name,
            'sections_url' => $collection->sections_url,
            'products_url' => $collection->products_url,
            'api_key'      => $collection->api_key,
            'sort'         => $collection->sort,
            'status'       => $collection->status,
        ]);
    }

    /**
     * @param Collection $collection
     */
    public function edit(Collection $collection)
    {
        $this->name = $collection->name;
        $this->sections_url = $collection->sections_url;
        $this->products_url = $collection->products_url;
        $this->api_key = $collection->api_key;
        $this->sort = $collection->sort;
        $this->status = $collection->status;
    }

    public function revokeSections()
    {
        $this->sections = [];
    }

    public function assignSection($id)
    {
        $sections = $this->sections;
        foreach ($sections as $section) {
            if ($section->profile_id == $id) {
                return;
            }
        }

        $sections[] = ImportProfileSections::create($id);
        $this->sections = $sections;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(ImportProfileSections::className(), ['profile_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%import_profiles}}';
    }

    public function attributeLabels()
    {
        return [
            'name'         => 'Название',
            'sections_url' => 'Урл REST сервиса для категорий',
            'products_url' => 'Урл REST сервиса для товаров',
            'api_key'      => 'Ключ доступа',
            'sort'         => 'Сортировка',
            'status'       => 'Активность',
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'relations' => [
                'class'     => SaveRelationsBehavior::class,
                'relations' => ['sections'],
            ],
        ];
    }
}
