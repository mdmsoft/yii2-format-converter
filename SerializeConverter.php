<?php

namespace mdm\converter;

/**
 * SerializeConverter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SerializeConverter extends BaseConverter
{
    /**
     * @var array function to serialize and unserialize data
     */
    public $serialize;

    /**
     * @var string 
     */
    public $format = 'serialize';

    /**
     * @var array 
     */
    public $serializeParams = [];

    /**
     * @var array 
     */
    public $unserializeParams = [];

    /**
     * @var array serialize function 
     */
    private $_serializes = [
        'serialize' => ['serialize', 'unserialize'],
        'json' => ['json_encode', 'json_decode'],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->serialize === null) {
            $this->serialize = $this->_serializes[$this->format];
        }
    }

    /**
     * @inheritdoc
     */
    protected function convertToLogical($value, $attribute)
    {
        $params = $this->unserializeParams;
        array_unshift($params, $value);
        return call_user_func_array($this->serialize[1], $params);
    }

    /**
     * @inheritdoc
     */
    protected function convertToPhysical($value, $attribute)
    {
        $params = $this->serializeParams;
        array_unshift($params, $value);
        return call_user_func_array($this->serialize[0], $params);
    }
}