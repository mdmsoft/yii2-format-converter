<?php

namespace mdm\converter;

/**
 * Description of NumeralConverter
 *
 * @author Misbahul D Munir (mdmunir) <misbahuldmunir@gmail.com>
 */
class NumeralConverter extends BaseConverter
{
    public $thousands_sep = ',';
    public $decimal_point = '.';
    public $decimals = 0;

    protected function convertToLogical($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return number_format($value, $this->decimals, $this->decimal_point, $this->thousands_sep);
    }

    protected function convertToPhysical($value)
    {
        if ($value === null || $value === '') {
            return $value;
        }
        $number = explode($this->decimal_point, $value, 2);

        return str_replace($this->thousands_sep, '', $number[0]) . (isset($number[1]) ? '.' . $number[1] : '');
    }
}
