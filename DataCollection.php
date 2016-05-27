<?php

namespace mdm\converter;

/**
 * Description of DataCollection
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DataCollection implements \IteratorAggregate, \ArrayAccess, \Countable
{
    use \yii\base\ArrayAccessTrait;

    protected $data;

    public function __construct($array)
    {
        $this->data = $array;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function copyFromArray($array)
    {
        $this->data = $array;
    }

    public function __get($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function __set($offset, $value)
    {
        $this->data[$offset] = $value;
    }
}
