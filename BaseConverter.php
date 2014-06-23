<?php

namespace mdm\converter;

use yii\base\NotSupportedException;

/**
 * Description of BaseConverter
 *
 * @author Misbahul D Munir (mdmunir) <misbahuldmunir@gmail.com>
 */
class BaseConverter extends \yii\base\Behavior
{
    public $attributes = [];
    public $isEmpty;

    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            $attrValue = $this->owner->{$this->attributes[$name]};
            return $this->convertToLogical($attrValue);
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->attributes[$name])) {
            $this->owner->{$this->attributes[$name]} = $this->convertToPhysical($value);
        } else {
            parent::__set($name, $value);
        }
    }

    public function canGetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canSetProperty($name, $checkVars);
    }

    protected function convertToPhysical($value)
    {
        throw new NotSupportedException(get_class($this) . ' does not support convertToPhysical().');
    }

    protected function convertToLogical($value)
    {
        throw new NotSupportedException(get_class($this) . ' does not support convertToLogical().');
    }

    public function isEmpty($value)
    {
        if ($this->isEmpty !== null) {
            return call_user_func($this->isEmpty, $value);
        } else {
            return $value === null || $value === '' || $value === [];
        }
    }
}