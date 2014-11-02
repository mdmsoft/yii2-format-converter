<?php

namespace mdm\converter;

/**
 * RelatedConverter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RelatedConverter extends BaseConverter
{
    /**
     * @var array 
     */
    private $_relations = [];

    /**
     * @var boolean 
     */
    public $cacheValue = true;

    /**
     * @var array 
     */
    private static $_values = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        foreach ($this->attributes as $attribute => $defition) {
            if (!isset($defition[3])) {
                $class = $defition[1];
                $defition[3] = $class::primaryKey()[0];
            }
            $this->attributes[$attribute] = $defition[0];
            $this->_relations[$attribute] = $defition;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function convertToLogical($value, $attribute)
    {
        /* @var $class \yii\db\ActiveRecord */
        list(, $class, $field, $key) = $this->_relations[$attribute];
        if ($this->cacheValue && isset(self::$_values[$class][$key][$value][$field])) {
            return self::$_values[$class][$key][$value][$field];
        } else {
            $related = $class::findOne([$key => $value]);
            if ($related) {
                if ($this->cacheValue) {
                    return self::$_values[$class][$key][$value][$field] = $related->$field;
                }
                return $related->$field;
            }
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    protected function convertToPhysical($value, $attribute)
    {
        /* @var $class \yii\db\ActiveRecord */
        list(, $class, $field, $key) = $this->_relations[$attribute];
        if ($this->cacheValue && isset(self::$_values[$class][$field][$value][$key])) {
            return self::$_values[$class][$field][$value][$field];
        } else {
            $related = $class::findOne([$field => $value]);
            if ($related) {
                if ($this->cacheValue) {
                    return self::$_values[$class][$field][$value][$key] = $related->$key;
                }
                return $related->$key;
            }
            return null;
        }
    }
}