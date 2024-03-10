<?php

declare(strict_types=1);

namespace App\Helper;

use ArrayAccess;
use Closure;
use Exception;

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
            static::setValue($data, $path, $replace, true, true);
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
    public static function setValue(array &$data, mixed $path, mixed $value, bool $onlyIfKeyExists = false, bool $strict = false): void
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
     * or an anonymous function returning the value. The anonymous function signature should be:
     * `function($array, $defaultValue)`.
     * The possibility to pass an array of keys is available since version 2.0.4.
     * @param mixed $default the default value to be returned if the specified array key does not exist. Not used when
     * getting value from an object.
     *
     * @throws Exception
     *
     * @return mixed the value of the element if found, default value otherwise
     */
    public static function getValue(mixed $array, mixed $key, mixed $default = null): mixed
    {
        if ($key instanceof Closure) {
            return $key($array, $default);
        }

        if (is_array($key)) {
            $lastKey = array_pop($key);
            foreach ($key as $keyPart) {
                $array = static::getValue($array, $keyPart);
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
            $array = static::getValue($array, substr($key, 0, $pos), $default);
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
     *
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

    public static function exportKeyPath(array $data, mixed $path, mixed $default = null): ?array
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
}
