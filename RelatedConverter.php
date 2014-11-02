<?php

namespace mdm\converter;

use Yii;
use yii\base\InvalidConfigException;

/**
 * RelatedConverter
 *
 * ~~~
 * // attach as behavior
 * [
 *     'class' => 'mdm\converter\RelatedConverter',
 *     'attributes => [
 *         'supplierName' => ['supplier', 'name'], // use avaliable relation
 *         'branchName' => [[Branch::className(), 'id' => 'branch_id'], 'name'], // use classname
 *     ]
 * ]
 * 
 * // then attribute directly
 * $model->supplierName = 'Donquixote Family';
 * $model->branchName = 'North Blue';
 * ~~~
 * 
 * @property \yii\db\ActiveRecord $owner
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
    public function attach($owner)
    {
        /* @var $owner \yii\db\ActiveRecord */
        foreach ($this->attributes as $attribute => $defition) {
            list($relation, $field) = $defition;
            if (is_string($relation)) {
                $r = $owner->getRelation($relation);
                $class = $r->modelClass;
                $link = $r->link;
            } elseif (is_array($relation)) {
                $class = $relation[0];
                $link = array_slice($relation, 1);
            } else {
                throw new InvalidConfigException("Invalid attribute definision for \"{$attribute}\"");
            }

            foreach ($link as $from => $to) {
                $this->_relations[$attribute] = [$class, $field, $from];
                $this->attributes[$attribute] = $to;
                break;
            }
        }
        parent::attach($owner);
    }

    /**
     * @inheritdoc
     */
    protected function convertToLogical($value, $attribute)
    {
        /* @var $class \yii\db\ActiveRecord */
        list($class, $field, $key) = $this->_relations[$attribute];
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
        list($class, $field, $key) = $this->_relations[$attribute];
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