<?php

if (! function_exists('url_cache_key')) {
    /**
     * Get Current Request Cache Key.
     *
     * @param bool $full full url
     * @return string
     */
    function url_cache_key($full = false)
    {
        $url = request()->getRequestUri();
        if ($full) {
            $url = request()->fullUrl();
        }

        $queryParams = request()->all();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        $rememberKey = sha1($fullUrl);

        return $rememberKey;
    }
}

if (! function_exists('full_url_cache_key')) {
    /**
     * Get Current Request Cache Key.
     *
     * @return string
     */
    function full_url_cache_key()
    {
        return url_cache_key(true);
    }
}
