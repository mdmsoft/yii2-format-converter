<?php

namespace mdm\converter;

use \ReflectionClass;
use yii\helpers\Inflector;

/**
 * Description of EnumConverter
 *
 * @author Misbahul D Munir (mdmunir) <misbahuldmunir@gmail.com>
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
        if (!isset(static::$_constants[$className])) {
            $ref = new ReflectionClass($className);
            static::$_constants[$className] = [];
            foreach ($ref->getConstants() as $constName => $constValue) {
                if ($this->enumPrefix === '' || strpos($constName, $this->enumPrefix) === 0) {
                    static::$_constants[$className][$constValue] = substr($constName, strlen($this->enumPrefix));
                }
            }
        }

        $str = isset(static::$_constants[$className][$value]) ? static::$_constants[$className][$value] : '';

        return $this->toWord ? Inflector::camel2words(strtolower($str)) : $str;
    }
}