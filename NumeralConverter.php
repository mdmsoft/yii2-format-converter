<?php

namespace mdm\converter;

/**
 * Description of NumeralConverter
 *
 * @author Misbahul D Munir (mdmunir) <misbahuldmunir@gmail.com>
 */
class NumeralConverter extends BaseConverter
{
    /**
     * @var string Thousands separator 
     */
    public $thousands_sep = ',';

    /**
     * @var string Decimal point 
     */
    public $decimal_point = '.';

    /**
     * @var integer Decimal precision
     */
    public $decimals = 0;

    /**
     * @inheritdoc
     */
    protected function convertToLogical($value, $attribute)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return number_format($value, $this->decimals, $this->decimal_point, $this->thousands_sep);
    }

    /**
     * @inheritdoc
     */
    protected function convertToPhysical($value, $attribute)
    {
        if ($value === null || $value === '') {
            return $value;
        }
        $number = explode($this->decimal_point, $value, 2);

        return str_replace($this->thousands_sep, '', $number[0]) . (isset($number[1]) ? '.' . $number[1] : '');
    }
}