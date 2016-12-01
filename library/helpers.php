<?php 

if (! function_exists('array_get'))
{

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) return $array;
        
        if (isset($array[$key])) return $array[$key];
        $tmp = explode('.', $key);
        foreach ($tmp as $segment)
        {
            if (! is_array($array) || ! array_key_exists($segment, $array))
            {return value($default);}
            
            $array = $array[$segment];
        }
        
        return $array;
    }


}


if (! function_exists('array_set'))
{

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    function array_set(&$array, $key, $value)
    {
        if (is_null($key)) return $array = $value;
        
        $keys = explode('.', $key);
        $count = count($keys);
        while ($count > 1)
        {
            $key = array_shift($keys);
            
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (! isset($array[$key]) || ! is_array($array[$key]))
            {
                $array[$key] = [];
            }
            
            $array = & $array[$key];
        }
        
        $array[array_shift($keys)] = $value;
        
        return $array;
    }
}

if (! function_exists('value'))
{

    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}

// 判断是否是json数据
function isValidJson($strJson) { 
    json_decode($strJson); 
    return (json_last_error() === JSON_ERROR_NONE); 
}

