<?php

namespace mdm\converter;

use ReflectionClass;

/**
 * Description of EnumTrait
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
trait EnumTrait
{
    /**
     * @var array
     */
    private static $_constants = [];

    /**
     * Get all constant name
     * @param string $prefix
     * @return array
     */
    public static function enums($prefix = '')
    {
        return array_flip(static::constants($prefix));
    }

    /**
     * Get all constant value
     * @param string $prefix
     * @return array
     */
    public static function constants($prefix = '')
    {
        $className = get_called_class();

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

    protected function getLogical($attribute, $prefix)
    {
        $enums = static::enums($prefix);
        return isset($enums[$this->$attribute]) ? $enums[$this->$attribute] : null;
    }

    protected function setLogical($attribute, $prefix, $value)
    {
        $constant = static::constants($prefix);
        if (isset($constant[strtoupper($value)])) {
            $this->$attribute = $constant[strtoupper($value)];
        } elseif ($value === null) {
            $this->$attribute = null;
        }
    }
}
