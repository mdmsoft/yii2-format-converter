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

    /**
     *
     * @param string $name
     * @return type
     */
    public function __get($name)
    {
        if (isset($this->enumAttributes, $this->enumAttributes[$name])) {
            list($attr, $prefix) = $this->enumAttributes[$name];
            $enums = static::enums($prefix);
            return isset($enums[$this->$attr]) ? $enums[$this->$attr] : null;
        } else {
            return parent::__get($name);
        }
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (isset($this->enumAttributes, $this->enumAttributes[$name])) {
            list($attr, $prefix) = $this->enumAttributes[$name];
            $constant = static::constants($prefix);
            if (isset($constant[strtoupper($value)])) {
                $this->$attr = $constant[strtoupper($value)];
            }
        } else {
            parent::__set($name, $value);
        }
    }
}
