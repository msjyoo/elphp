<?php

namespace Elphp\Component\ArrayTools;

use Ouzo\Utilities\Arrays;

/**
 * Flatten an array and return all values in a one-dimensional array. Keys are dropped.
 *
 * @param array $array
 *
 * @return array
 */
function array_flatten(array $array)
{
    return Arrays::flatten($array);
}

/**
 * Works similar to end(), but without pass by reference.
 *
 * @param array $array
 *
 * @return mixed
 */
function last(array $array)
{
    return end($array);
}

/**
 * Works similar to reset(), but without pass by reference.
 *
 * @param array $array
 *
 * @return mixed
 */
function first(array $array)
{
    return reset($array);
}