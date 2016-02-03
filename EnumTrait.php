<?php

namespace mdm\converter;

/**
 * Description of EnumTrait
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
trait EnumTrait
{

    /**
     * Get all constant name
     * @param string $prefix
     * @return array
     */
    public static function enums($prefix = '')
    {
        return EnumConverter::names($this, $prefix);
    }

    /**
     * Get all constant value
     * @param string $prefix
     * @return array
     */
    public static function constants($prefix = '')
    {
        return EnumConverter::values($this, $prefix);
    }
}
