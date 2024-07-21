<?php

declare(strict_types=1);

namespace App\Helpers;

class HtmlHelper
{
    protected const TAG_LINK = 'link';
    protected const TAG_SCRIPT = 'script';

    public static function linkUrl(string $url, array $options = []): string
    {
        $options = array_merge([
            'href' => $url,
        ], $options);
        return self::tag(self::TAG_LINK, null, $options);
    }

    public static function cssUrl(string $url, array $options = []): string
    {
        $options = array_merge([
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'href' => $url,
        ], $options);
        return self::tag(self::TAG_LINK, null, $options);
    }

    public static function scriptUrl(string $url, array $options = []): string
    {
        $options = array_merge([
            'src' => $url,
        ], $options);
        return self::tag(self::TAG_SCRIPT, null, $options);
    }

    public static function tag(string $name, ?string $value = null, array $options = []): string
    {
        $begin = '<' . $name;
        $end = $value === null ? '/>' : '>' . $value . '</' . $name . '>';
        $attributes = ArrayHelper::reduceAssoc(
            $options,
            fn($carry, $value, $key) => $carry[] = is_numeric($key) ? $value : "{$key}=\"{$value}\"",
            [],
        );
        return $begin . ' ' . implode(' ', $attributes) . $end;
    }

}
