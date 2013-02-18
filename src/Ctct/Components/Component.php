<?php
namespace Ctct\Components;

/**
 * Super class for all components
 */
abstract class Component
{

    /**
     * Get the requested value from an array, or return the default
     * @param array $array - array to search for the provided array key
     * @param string $item - array key to look for
     * @param string $default - value to return if the item is not found, default is null
     * @return mixed
     */
    protected static function getValue(array $array, $item, $default = null)
    {
        return (isset($array[$item])) ? $array[$item] : $default;
    }
}
