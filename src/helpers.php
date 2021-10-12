<?php

if (! file_exists('dcatadmin_bootstrap')) {
    /**
     * bootstrap laravel-init-template dcat admin
     */
    function dcatadmin_bootstrap() {
        if (file_exists($dcatAdminBootstrapFile = base_path('vendor/zhenmu/laravel-init-template/src/DcatAdmin/bootstrap.php'))) {
            require_once $dcatAdminBootstrapFile;
        }
    }
}

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

if (! function_exists('after_seconds')) {
    /**
     * Get Current Request Cache Key.
     *
     * @param int $value
     * @return \Carbon\Carbon
     */
    function after_seconds($value = 1)
    {
        return now()->addSeconds($value);
    }
}

if (! function_exists('after_minutes')) {
    /**
     * Get Current Request Cache Key.
     *
     * @param int $value
     * @return \Carbon\Carbon
     */
    function after_minutes($value = 1)
    {
        return now()->addMinutes($value);
    }
}

if (! function_exists('after_hours')) {
    /**
     * Get Current Request Cache Key.
     *
     * @param int $value
     * @return \Carbon\Carbon
     */
    function after_hours($value = 1)
    {
        return now()->addHours($value);
    }
}

if (! function_exists('after_days')) {
    /**
     * Get Current Request Cache Key.
     *
     * @param int $value
     * @return \Carbon\Carbon
     */
    function after_days($value = 1)
    {
        return now()->addDays($value);
    }
}

if (! function_exists('after_months')) {
    /**
     * Get Current Request Cache Key.
     *
     * @param int $value
     * @return \Carbon\Carbon
     */
    function after_months($value = 1)
    {
        return now()->addMonths($value);
    }
}

if (! function_exists('after_years')) {
    /**
     * Get Current Request Cache Key.
     *
     * @param int $value
     * @return \Carbon\Carbon
     */
    function after_years($value = 1)
    {
        return now()->addYears($value);
    }
}
