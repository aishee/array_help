<?php

class Arr
{
    /**
     * Return two arrays containing elements for which
     * Filter returned true and false, respectively
     * Tip: Use a list()
     * @param $array
     * @param $callback the callback function returns true or false
     * It has two arguments: $value as $key 
     * @return array
     */
    public static function split($array, $callback)
    {
        $trued = [];
        $falsed = [];
        foreach ($array as $key => $value) {
            if($callback($value, $key))
            {
                $trued[] = $value;
            }
             else{
                $falsed[] = $value;
             }
        }
        return [$trued, $falsed];
    }
    /**
     * Checks all given keys for all elements of the array
     * @param string[] $keys the keys
     * @param array[] $items with elements of the array
     * @return bool
     */
    public static function isKeysInItems($keys, $items) {
        foreach($items as $item)
        {
            foreach($keys as $key)
            {
                if(!isset($item[$key]))
                {
                    return false;
                }
            }
        }
        return true;
    }
    /**
     * An analog array_column(), but with the support ArrayAccess
     * @param ArrayAccess $array
     * @param string $key
     * @return array
     */
    public static function column($array, $key)
    {
        $result = [];
        foreach($array as $item)
        {
            $result[] = $item[$key];
        }
        return $result;
    }
    
    /**
     * Analog of array_values()
     * @param array|Traversable $array
     * @param string $key
     * @return array
     */
    public static function values($array, $key)
    {
        $result = [];
        foreach($array as $elem)
        {
            $result[] = $elem[$key];
        }
        return $result;
    }
    /**
     * Creates a new array based on another, using as keys
     * values on the key elements of $keyKeys, and for values of $keyValues
     * for example: [['id' => 7, 'value' => 'Ivan', 'age' => 47], [...]]
     * $keyKeys = 'id'
     * $keyValues = 'value'
     * we obtain an array: [7 => 'Ivan', ...]
     * @param array|ArrayAccess $array
     * @param string $keyKeys
     * @param string $keyValues
     * @return array
     */
    public static function plainify($array, $keyKeys, $keyValues)
    {
        return self::combine(self::column($array, $keyKeys), self::column($array, $keyValues));
    }
    public static function buildOne($source, $schema)
    {
        $fnBuild = function($source, $schema) use(&$fnBuild) {
            $result = [];
            foreach ($schema as $key => $value)
            {
                if (is_array($value)) {
                    //recursive
                    $result[$key] = $fnBuild($source, $value);
                } else {
                    $result[$key] = $source[$value];
                }
            }
            return $result;
        };
        return $fnBuild($source, $schema);
    }
    public static function buildAll($sources, $schema) {
        $result = [];
        foreach ($sources as $source) {
            $result[] = self::buildOne($source, $schema);
        }
        return $result;
    }
    /**
     * The analogue in_array() with support Traversable
     * @param Traversable|array $array
     * @param callable $callback Function contains 2 arguments: $currentElement and $reduceValue
     * @param mixed $initial Start value of $reduceValue
     * @return mixed
     */
    public static function reduce($array, $callback, $initial)
    {
        foreach ($array as $item)
        {
            $initial = $callback($item, $initial);
        }
        return $initial;
    }
    /**
     * Analog array_combine() supporting Traversable
     * @param Traversable|array $keys
     * @param Traversable|array $values
     * @return array
     *
     */
    public static function combine($keys, $values)
    {
        $result = [];
        foreach($keys as $keys => $value)
        {
            $result [(string)$value] = $values[$key];
        }
        return $result;
    }
    /**
     * Returns a new array build on the unique values of $array 
     * is calculated by the uniqueness fields listed in the $keys 
     * @param $array array|ArrayAccess 
     * @param $keys string[]
     * @return array
     */
    
    public static function uniquify($array, $keys)
    {
        $uniquified = [];
        foreach ($array as $arrayItem) {
            $keyParts = [];
            foreach ($keys as $key) {
                $keyParts[] = $arrayItem[$key];
            }
            $uniquified[implode(':', $keyParts)] = $arrayItem;
        }
        return array_values($uniquified);
    }
    public static function keying($array, $key)
    {
        $keyed = [];
        foreach ($array as $item) {
            $keyed[$item[$key]] = $item;
        }
        return $keyed;
    }
    
    public static function keyingNotUnique($array, $key)
    {
        $keyed = [];
        foreach($array as $item)
        {
            if(!isset($keyed[$item[$key]]))
            {
                $keyed[$item[$key]] = [$item];
            }
            else {
                $keyed[$item[$key]][] = $item;
            }
        }
        return $keyed;
    }
    
    public static function getRandomKey($array)
    {
        if (0 === ($count = count($array)))
        {
            return false;
        }
        $keys = self::keys($array);
        return $keys[rand(0, $count)];
    }
    
    public static function getRandomElement($array)
    {
        if($key = self::getRandomKey($array))
        {
            return $array[$key];
        }
        return false;
    }
    
    public static function renameKeys($source, $map)
    {
        foreach ($map as $oldkey => $newkey)
        {
            $source[$newkey] = $source[$oldkey];
            unset($source[$oldkey]);
        }
        return $source;
    }
}