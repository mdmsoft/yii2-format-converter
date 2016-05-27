<?php

namespace mdm\converter;

use Yii;
use yii\base\NotSupportedException;

/**
 * Description of BaseConverter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class BaseConverter extends \yii\base\Behavior
{
    /**
     * @var array Attribute map for logical to physical
     */
    public $attributes = [];

    /**
     * @var \Closure callback to check value is empty
     * 
     * ```php
     * function($value){
     * 
     * }
     * ```
     */
    public $isEmpty;

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            $attrValue = $this->owner->{$this->attributes[$name]};
            return $this->convertToLogical($attrValue, $name);
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (isset($this->attributes[$name])) {
            $this->owner->{$this->attributes[$name]} = $this->convertToPhysical($value, $name);
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canGetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canSetProperty($name, $checkVars);
    }

    /**
     * Convert value to physical format
     * @param mixed $value value to converted
     * @param string $attribute Logical attribute
     * @return mixed Converted value
     */
    protected function convertToPhysical($value, $attribute)
    {
        throw new NotSupportedException(get_class($this) . ' does not support convertToPhysical().');
    }

    /**
     * Convert value to logical format
     * @param mixed $value value to converted
     * @param string $attribute Logical attribute
     * @return mixed Converted value
     */
    protected function convertToLogical($value, $attribute)
    {
        throw new NotSupportedException(get_class($this) . ' does not support convertToLogical().');
    }

    /**
     * Check empty value
     * @param mixed $value
     * @return boolean
     */
    public function isEmpty($value)
    {
        if ($this->isEmpty !== null) {
            return call_user_func($this->isEmpty, $value);
        } else {
            return $value === null || $value === '' || $value === [];
        }
    }
}
