<?php

namespace mdm\converter;

/**
 * Description of DateConverter
 *
 * @author Misbahul D Munir (mdmunir) <misbahuldmunir@gmail.com>
 */
class DateConverter extends BaseConverter
{
    public $logicalFormat = 'd-m-Y';
    public $physicalFormat = 'Y-m-d';

    protected function convertToLogical($value)
    {
        if ($this->isEmpty($value)) {
            return null;
        }
        $date = @date_create_from_format($this->physicalFormat, $value);

        return $date === false ? null : $date->format($this->logicalFormat);
    }

    protected function convertToPhysical($value)
    {

        if ($this->isEmpty($value)) {
            return null;
        }
        $date = @date_create_from_format($this->logicalFormat, $value);

        return $date === false ? null : $date->format($this->physicalFormat);
    }
}
