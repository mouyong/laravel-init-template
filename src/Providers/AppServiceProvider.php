<?php

namespace ZhenMu\LaravelInitTemplate\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use ZhenMu\LaravelInitTemplate\Console\Commands\AddUser;
use ZhenMu\LaravelInitTemplate\Models\Address;
use ZhenMu\LaravelInitTemplate\Repositories\UserRepository;

class AppServiceProvider extends ServiceProvider
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
        Schema::defaultStringLength(191);

        $this->publishConfig();
        $this->publishYapi();

        $this->bootBuilder();
        $this->mapRoutes();
        $this->registerMigrations();
    }

    private function bootBuilder()
    {
        $pagenateFunc = function ($columns = [], $per_page = null) {
            if (empty($columns)) {
                $columns = \request('columns', ['*']);
            }

            if (request('all') || request('export')) {
                return $this->get($columns);
            }

            if (is_null($per_page)) {
                $per_page = (int) request(config('laravel-init-template.request.per_page', 'per_page'), 20);
            }

            $per_page <= 100 ? $per_page : 100;

            return $this->paginate($per_page, $columns);
        };

        \Illuminate\Database\Eloquent\Builder::macro('result', $pagenateFunc);
        \Illuminate\Database\Query\Builder::macro('result', $pagenateFunc);
    }

    private function mapRoutes()
    {
        if (file_exists($path = __DIR__.'/../../routes/api.php')) {
            Route::prefix('api')->group($path);
        }
    }

    private function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'laravel-template-migrations');
    }

    private function publishConfig()
    {
        $this->mergeConfigFrom($path = __DIR__.'/../../config/laravel-init-template.php', 'laravel-init-template');

        $this->publishes([
            $path => config_path('laravel-init-template.php'),
        ], 'laravel-template-config');
    }

    private function publishYapi()
    {
        $this->publishes([
            __DIR__.'/../../stubs/Tests/Traits' => base_path('tests/Traits'),
            __DIR__.'/../../stubs/Tests/Yapi' => base_path('tests/Yapi'),
        ], 'laravel-template-yapi');
    }
}
