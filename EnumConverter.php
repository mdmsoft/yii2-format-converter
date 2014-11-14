<?php

namespace mdm\converter;

use \ReflectionClass;
use yii\helpers\Inflector;

/**
 * EnumConverter
 * Get constant name instead of constant value.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class EnumConverter extends BaseConverter
{
    /**
     * @var array
     */
    public $enum = [];

    /**
     * @var string
     */
    public $enumPrefix = '';

    /**
     * @var boolean
     */
    public $toWord = true;

    /**
     * @var array
     */
    private static $_constants = [];

    /**
     * @inheritdoc
     */
    protected function convertToLogical($value, $attribute)
    {
        if ($this->isEmpty($value)) {
            return null;
        }

        if (isset($this->enum[$value])) {
            return $this->enum[$value];
        }
        $className = get_class($this->owner);
        if (!isset(static::$_constants[$className][$this->enumPrefix])) {
            $ref = new ReflectionClass($className);
            static::$_constants[$className][$this->enumPrefix] = [];
            foreach ($ref->getConstants() as $constName => $constValue) {
                if ($this->enumPrefix === '' || strpos($constName, $this->enumPrefix) === 0) {
                    static::$_constants[$className][$this->enumPrefix][$constValue] = substr($constName, strlen($this->enumPrefix));
                }
            }
        }

        $str = isset(static::$_constants[$className][$this->enumPrefix][$value]) ? static::$_constants[$className][$this->enumPrefix][$value] : '';

        return $this->toWord ? Inflector::camel2words(strtolower($str)) : $str;
    }
}