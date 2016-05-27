<?php

namespace mdm\converter;

/**
 * Description of ArrayConverter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ArrayConverter extends BaseConverter
{
    private $_data = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return[
            'beforeSave' => 'beforeSave'
        ];
    }

    public function beforeSave($event)
    {
        foreach ($this->attributes as $to => $from) {
            $val = isset($this->_data[$to]) ? $this->_data[$to]->toArray() : null;
            $this->owner->$from = $val === null ? null : json_encode($val);
        }
    }

    /**
     * @inheritdoc
     */
    protected function convertToLogical($value, $attribute)
    {
        if (!isset($this->_data[$attribute])) {
            $this->_data[$attribute] = new DataCollection($value === null || $value === '' ? null : json_decode($value, true));
        }
        return $this->_data[$attribute];
    }

    /**
     * @inheritdoc
     */
    protected function convertToPhysical($value, $attribute)
    {
        if (!isset($this->_data[$attribute])) {
            $this->_data[$attribute] = new DataCollection($value === '' ? null : $value);
        } else {
            $this->_data[$attribute]->copyFromArray($value === '' ? null : $value);
        }
        $this->owner->{$this->attributes[$attribute]} = $value === '' || $value === null ? null : json_encode($value);
    }
}
