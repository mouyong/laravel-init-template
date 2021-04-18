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

        $this->bootRepository();
        $this->bootBuilder();
        $this->mapRoutes();
        $this->registerMigrations();
        $this->registerCommands();
        $this->publishYapi();
    }

    private function bootRepository()
    {
        $classMap = [
            UserRepository::class => 'App\Repositories\UserRepository',

            Address::class => 'App\Models\Address',
        ];

        foreach ($classMap as $k => $v) {
            if (! class_exists($v)) {
                continue;
            }

            $this->app->bind($k, $v);
        }
    }

    private function bootBuilder()
    {
        $pagenateFunc = function () {
            if (request('all') || request('export')) {
                return $this->get(request('columns', ['*']));
            }

            $per_page = (int) request('per_page', 20);

            return $this->paginate($per_page <= 100 ? $per_page : 100, request('columns', ['*']));
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

    private function registerCommands()
    {
        $this->commands([
            AddUser::class,
        ]);
    }

    private function publishYapi()
    {
        $this->publishes([
            __DIR__.'/../../stubs/Tests/Traits' => base_path('tests/Traits'),
            __DIR__.'/../../stubs/Tests/Yapi' => base_path('tests/Yapi'),
        ], 'laravel-template-yapi');
    }
}
