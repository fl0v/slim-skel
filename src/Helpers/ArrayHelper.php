<?php

declare(strict_types=1);

namespace App\Helpers;

use ArrayAccess;
use Closure;
use Exception;

/**
 * @see https://github.com/yiisoft/yii2/blob/master/framework/helpers/BaseArrayHelper.php
 * @see https://github.com/laravel/framework/blob/8.x/src/Illuminate/Collections/Arr.php
 * @see https://github.com/bayfrontmedia/php-array-helpers/blob/master/src/Arr.php
 */
class ArrayHelper
{
    /**
     * @param array $data data to be sanitised
     * @param array $keys keys can be set of paths ['path.to.key1','path.to.key2',...]
     * @param string|null $replace by default will set values as null
     */
    public static function sanitiseKeys(array &$data, array $keys, ?string $replace = null): void
    {
        foreach ($keys as $path) {
            static::set($data, $path, $replace, true, true);
        }
    }

    /**
     * @param array $data data to be changed based on provided path and value
     * @param string|string[] $path key path as string or set of canonical key names ('path.to.key' or ['path','to','key'])
     * @param mixed $value any value to set in provided array for given key
     * @param bool $onlyIfKeyExists if true will only change existing key values without adding new ones
     * @param bool $strict if true and any part of the key path exists but is not array,
     *                     will be converted to array with existing not null value at index 0 (see unit tests)
     */
    public static function set(array &$data, mixed $path, mixed $value, bool $onlyIfKeyExists = false, bool $strict = false): void
    {
        if (empty($path)) {
            return;
        }
        $keyParts = is_array($path) ? $path : explode('.', $path); // normalise path as array
        while (count($keyParts) > 1) {
            $key = array_shift($keyParts);
            if (!array_key_exists($key, $data)) { // for each key part make sure it exists
                if ($onlyIfKeyExists) {
                    return;
                }
                $data[$key] = [];
            }
            if (!is_array($data[$key])) { // if it exists but is not array
                if ($strict) {
                    return; // in strict mode will stop
                }
                // otherwise will convert it to compatible array
                // if old value is not null, will be kept at index 0
                $data[$key] = $data[$key] === null ? [] : [$data[$key]];
            }
            $data = &$data[$key]; // move pointer inside array to current key part
        }
        // set last key part
        $key = array_shift($keyParts);
        if (!array_key_exists($key, $data) && $onlyIfKeyExists) {
            return;
        }
        $data[$key] = $value;
    }

    /**
     * @param array|object $array array or object to extract value from
     * @param string|Closure|array $key key name of the array element, an array of keys or property name of the object,
     *                                  or an anonymous function returning the value. The anonymous function signature should be:
     *                                  `function($array, $defaultValue)`.
     *                                  The possibility to pass an array of keys is available since version 2.0.4.
     * @param mixed $default the default value to be returned if the specified array key does not exist. Not used when
     *                       getting value from an object.
     * @return mixed the value of the element if found, default value otherwise
     * @throws Exception
     */
    public static function get(mixed $array, mixed $key, mixed $default = null): mixed
    {
        if ($key instanceof Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::get($array, $keyPart);
            }
            $key = $lastKey;
        }

        if (is_object($array) && property_exists($array, $key)) {
            return $array->$key;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if ($key && ($pos = strrpos($key, '.')) !== false) {
            $array = static::get($array, substr($key, 0, $pos), $default);
            $key = substr($key, $pos + 1);
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        if (is_object($array)) {
            // this is expected to fail if the property does not exist, or __get() is not implemented
            // it is not reliably possible to check whether a property is accessible beforehand
            try {
                return $array->$key;
            } catch (Exception $e) {
                if ($array instanceof ArrayAccess) {
                    return $default;
                }
                throw $e;
            }
        }

        return $default;
    }

    /**
     * @param array $data
     * @param array $keys
     * @return array returned array will have strict structure that matches the provided keys with values from data
     */
    public static function export(array $data, array $keys): array
    {
        $result = [];
        foreach ($keys as $path) {
            $result = array_merge($result, self::exportKeyPath($data, $path));
        }

        return $result;
    }

    public static function exportKeyPath(array $data, mixed $path, mixed $default = null): array
    {
        if (empty($path)) {
            return [];
        }
        $keyParts = is_array($path) ? $path : explode('.', $path); // normalise path as array
        $first = array_shift($keyParts);
        if (empty($keyParts)) { // was last key part
            return [$first => $data[$first] ?? $default]; // export as array
        }
        $data = !empty($data[$first]) && is_array($data[$first]) ? $data[$first] : [];

        return [$first => self::exportKeyPath($data, $keyParts, $default)]; // go deeper for remaining key parts
    }

    /**
     * Converts a multidimensional array to a single depth "dot" notation array,
     * optionally prepending a string to each array key.
     * The key values will never be an array, even if empty. Empty arrays will be dropped.
     *
     * @see https://stackoverflow.com/a/10424516
     * @param array $array
     * @param string $prepend
     */
    public static function dot(array $array, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, self::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Converts array in "dot" notation to a standard multidimensional array.
     *
     * @param array $array (Array in "dot" notation)
     */
    public static function undot(array $array): array
    {
        $return = [];
        foreach ($array as $key => $value) {
            self::set($return, $key, $value);
        }

        return $return;
    }

    /**
     * Checks if array key exists and not null using "dot" notation.
     *
     * @param array $array (Original array)
     * @param string $key (Key to check in "dot" notation)
     * @throws Exception
     */
    public static function has(array $array, string $key): bool
    {
        return null !== self::get($array, $key);
    }

    /**
     * Returns an array of values for a given key from an array using "dot" notation.
     *
     * @param array $array (Original array)
     * @param string $value (Value to return in "dot" notation)
     * @param string|null $key (Optionally how to key the returned array in "dot" notation)
     * @throws Exception
     */
    public static function pluck(array $array, string $value, ?string $key = null): array
    {
        $results = [];
        foreach ($array as $item) {
            $itemValue = self::get($item, $value);
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = self::get($item, $key);
                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Remove a single key, or an array of keys from a given array using "dot" notation.
     *
     * @param array $array (Original array)
     * @param array|string $keys (Key(s) to forget in "dot" notation)
     */
    public static function forget(array &$array, array|string $keys): void
    {
        $original = &$array;
        foreach ((array)$keys as $key) {
            $parts = explode('.', $key);
            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                }
            }
            unset($array[array_shift($parts)]);
            // Clean up after each iteration
            $array = &$original;
        }
    }

    /**
     * Returns the original array except given key(s).
     *
     * @param array $array (Original array)
     * @param array|string $keys (Keys to remove)
     */
    public static function except(array $array, array|string $keys): array
    {
        return array_diff_key($array, array_flip((array)$keys));
    }

    /**
     * Returns only desired key(s) from an array.
     *
     * @param array $array (Original array)
     * @param array|string $keys (Keys to return)
     */
    public static function only(array $array, array|string $keys): array
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }

    /**
     * Returns array of missing keys from the original array, or an empty array if none are missing.
     *
     * @param array $array (Original array)
     * @param array|string $keys (Keys to check)
     */
    public static function missing(array $array, array|string $keys): array
    {
        return array_values(array_flip(array_diff_key(array_flip((array)$keys), $array)));
    }

    /**
     * Checks if keys are missing from the original array.
     *
     * @param array $array (Original array)
     * @param array|string $keys (Keys to check)
     */
    public static function isMissing(array $array, array|string $keys): bool
    {
        return (bool)self::missing($array, $keys);
    }

    /**
     * Sort a multidimensional array by a given key in ascending (optionally, descending) order.
     *
     * @param array $array (Original array)
     * @param string $key (Key name to sort by)
     * @param bool $descending (Sort descending)
     */
    public static function multisort(array $array, string $key, bool $descending = false): array
    {
        $columns = array_column($array, $key);

        if (false === $descending) {
            array_multisort($columns, SORT_ASC, $array, SORT_NUMERIC);
        } else {
            array_multisort($columns, SORT_DESC, $array, SORT_NUMERIC);
        }

        return $array;
    }

    /**
     * @param array $array (Original array)
     * @param array $keys (Key/value pairs to rename)
     * @return array copy of orginal array with renamed keys in original order.
     */
    public static function renameKeys(array $array, array $keys): array
    {
        $new_array = [];
        foreach ($array as $k => $v) {
            if (array_key_exists($k, $keys)) {
                $new_array[$keys[$k]] = $v;
            } else {
                $new_array[$k] = $v;
            }
        }

        return $new_array;
    }

    /**
     * @param array $array (Original array)
     * @param array $order enforrce key order
     * @return array original array sorted by $order of keys.
     */
    public static function order(array $array, array $order): array
    {
        return self::only(array_replace(array_flip($order), $array), array_keys($array));
    }

    /**
     * @return string url query string built from key value array
     */
    public static function query(array $array): string
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * @param array $array
     * @param array $values
     * @return array values that exist in first array (dot format).
     */
    public static function getAnyValues(array $array, array $values): array
    {
        return array_intersect($values, static::dot($array));
    }

    /**
     * @param array $array
     * @param array $values
     * @return bool if any values exist in a given array.
     */
    public static function hasAnyValues(array $array, array $values): bool
    {
        return !empty(self::getAnyValues($array, $values));
    }

    /**
     * @param array $array
     * @param array $values
     * @return bool if all values exist in a given array.
     */
    public static function hasAllValues(array $array, array $values): bool
    {
        return count(array_intersect($values, static::dot($array))) == count($values);
    }

    public static function reduceAssoc(array $array, callable $callback, mixed $initial = null): mixed
    {
        $carry = $initial;
        foreach ($array as $key => $value) {
            $carry = $callback($carry, $value, $key);
        }

        return $carry;
    }
}
