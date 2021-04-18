<?php

namespace ZhenMu\LaravelInitTemplate\Utils;

class Url
{
    public static function parse(string $url, callable $callable = null)
    {
        $url_info = parse_url($url);
        parse_str($url_info['query'], $query);

        if (! is_null($callable)) {
            $callable($query, $url);
        }

        $withoutQueryUrl = static::getWithoutQueryUrl($url);

        $url = $withoutQueryUrl.http_build_query($query);

        return compact('url', 'url_info', 'query');
    }

    protected static function getQueryIndexWithUrl(string $url)
    {
        $length = -1;
        // 获取 ? 所在的位置
        if ($index = stripos($url, '?')) {
            $length = $index + 1;
        }

        return $length;
    }

    protected static function getWithoutQueryUrl(string $url)
    {
        return substr($url, 0, static::getQueryIndexWithUrl($url));
    }
}