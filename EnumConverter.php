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
        $names = static::names($this->owner, $this->enumPrefix);

        $str = isset($names[$value]) ? $names[$value] : '';

        return $this->toWord ? Inflector::camel2words(strtolower($str)) : $str;
    }

    /**
     * @inheritdoc
     */
    protected function convertToPhysical($name, $attribute)
    {
        foreach ($this->enum as $value => $const) {
            if ($const == $name) {
                return $value;
            }
        }

        $values = static::values($this->owner, $this->enumPrefix);
        return isset($values[strtoupper($name)]) ? $values[strtoupper($name)] : null;
    }

    /**
     * Get all constant value
     * @param string $className
     * @param string $prefix
     * @return array
     */
    public static function values($className, $prefix = '')
    {
        if (is_object($className)) {
            $className = get_class($className);
        }
        if (!isset(self::$_constants[$className][$prefix])) {
            $ref = new ReflectionClass($className);
            self::$_constants[$className][$prefix] = [];
            foreach ($ref->getConstants() as $constName => $constValue) {
                if ($prefix === '' || strpos($constName, $prefix) === 0) {
                    self::$_constants[$className][$prefix][substr($constName, strlen($prefix))] = $constValue;
                }
            }
        }

        return self::$_constants[$className][$prefix];
    }

    /**
     * Get all constant name
     * @param string $className
     * @param string $prefix
     * @return array
     */
    public static function names($className, $prefix = '')
    {
        return array_flip(static::values($className, $prefix));
    }
}
