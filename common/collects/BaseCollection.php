<?php

namespace common\collects;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class BaseCollection
 * @package common\collects
 */
abstract class BaseCollection extends Model
{
    /**
     * @var Model[]|array[]
     */
    public $collections = [];

    abstract protected function internalCollections();

    /**
     * @param array $data
     * @param null  $formName
     *
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $success = parent::load($data, $formName);

        foreach ($this->collections as $name => $collection) {
            if (is_array($collection)) {
                $success = Model::loadMultiple($collection, $data, $formName === null ? null : $name) && $success;
            } else {
                $success = $collection->load($data, $formName !== '' ? null : $name) && $success;
            }
        }

        return $success;
    }

    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     *
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $parentNames = $attributeNames !== null ? array_filter((array)$attributeNames, 'is_string') : null;
        $success = parent::validate($parentNames, $clearErrors);
        foreach ($this->collections as $name => $collection) {
            if (is_array($collection)) {
                $success = Model::validateMultiple($collection) && $success;
            } else {
                $innerNames = $attributeNames !== null ? ArrayHelper::getValue($attributeNames, $name) : null;
                $success = $collection->validate($innerNames ?: null, $clearErrors) && $success;
            }
        }

        return $success;
    }

    /**
     * @param null $attribute
     *
     * @return bool
     */
    public function hasErrors($attribute = null)
    {
        if ($attribute !== null) {
            return parent::hasErrors($attribute);
        }
        if (parent::hasErrors($attribute)) {
            return true;
        }
        foreach ($this->collections as $name => $collection) {
            if (is_array($collection)) {
                foreach ($collection as $i => $item) {
                    if ($item->hasErrors()) {
                        return true;
                    }
                }
            } else {
                if ($collection->hasErrors()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getFirstErrors()
    {
        $errors = parent::getFirstErrors();
        foreach ($this->collections as $name => $collection) {
            if (is_array($collection)) {
                foreach ($collection as $i => $item) {
                    foreach ($item->getFirstErrors() as $attribute => $error) {
                        $errors[$name . '.' . $i . '.' . $attribute] = $error;
                    }
                }
            } else {
                foreach ($collection->getFirstErrors() as $attribute => $error) {
                    $errors[$name . '.' . $attribute] = $error;
                }
            }
        }

        return $errors;
    }

    /**
     * @param string $name
     *
     * @return array|mixed|Model
     */
    public function __get($name)
    {
        if (isset($this->collections[$name])) {
            return $this->collections[$name];
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->internalCollections(), true)) {
            $this->collections[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->collections[$name]) || parent::__isset($name);
    }
}