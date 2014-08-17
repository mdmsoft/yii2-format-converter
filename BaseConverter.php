<?php

namespace mdm\converter;

use yii\base\NotSupportedException;
use Yii;
use yii\helpers\VarDumper;

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
            try {
                $trace = "Get logical value of {$name}, original value is ";
                $trace .= VarDumper::dumpAsString($attrValue);
                Yii::trace($trace, static::className());
            } catch (\Exception $exc) {

            }

            return $this->convertToLogical($attrValue);
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->attributes[$name])) {
            try {
                $trace = "Set physical value of {$name}, original value is ";
                $trace .= VarDumper::dumpAsString($value);
                Yii::trace($trace, static::className());
            } catch (\Exception $exc) {

            }
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
