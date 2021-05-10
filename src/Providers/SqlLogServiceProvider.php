<?php

namespace ZhenMu\LaravelInitTemplate\Providers;

use Illuminate\Support\ServiceProvider;

class SqlLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerQueryLogger();
    }

    /**
     * 开发时 SQL 耗时查询日志
     */
    protected function registerQueryLogger()
    {
        if (!$this->app['config']->get('app.debug')) {
            return;
        }

        $config = config('laravel-init-template.logging.sql');

        if ($config['enable'] === false) {
            return;
        }

        $this->app['config']->set('logging.channels.sql', config('laravel-init-template.logging.sql'));

        \Illuminate\Support\Facades\DB::listen(function (\Illuminate\Database\Events\QueryExecuted $query) {
            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);
            $bindings            = $query->connection->prepareBindings($query->bindings);
            $pdo                 = $query->connection->getPdo();
            $realSql             = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            $duration            = $this->formatDuration($query->time / 1000);
            \Illuminate\Support\Facades\Log::channel('sql')->debug(sprintf('[%s] %s | %s: %s', $duration, $realSql, request()->method(), request()->getRequestUri()));
        });
    }

    /**
     * 时间单位转换
     *
     * @param $seconds
     *
     * @return string
     */
    private function formatDuration($seconds)
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000) . 'μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2) . 'ms';
        }

        return round($seconds, 2) . 's';
    }
}
