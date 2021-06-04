<h1 align="center"> laravel-init-template </h1>

<p align="center"> .</p>


## Installing

```shell
composer require zhenmu/laravel-init-template -vvv

php artisan vendor:publish --provider "ZhenMu\LaravelInitTemplate\Providers\AppServiceProvider"
```

## Usage

### 项目

- app.php
```
'name' => env('APP_NAME', 'Laravel'),
'env' => env('APP_ENV', 'production'),
'debug' => (bool) env('APP_DEBUG', false),
'url' => env('APP_URL', 'http://localhost'),
'timezone' => 'PRC',
'locale' => 'zh_CN',
'faker_locale' => 'zh_CN',
```
- .env
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### sql 日志

日志路径：storage/logs/mysql-logs/mysql.log

```shell .env
APP_SQL_LOG_ENABLE=true
```

### 控制器

```app/Http/Controllers/Controller.php
<?php

namespace App\Http\Controllers;

use ZhenMu\LaravelInitTemplate\Http\Controllers\BaseController;

class Controller extends BaseController
{
}

```

### 响应资源

- 单资源

```app/Http/Resources/DemoResource.php
use ZhenMu\LaravelInitTemplate\Http\Resources\BaseResource;

class DemoResource extends BaseResource
{
}
```

- 集合资源

```app/Http/Resources/DemoCollectionResource.php
use ZhenMu\LaravelInitTemplate\Http\Resources\BaseResourceCollection;

class DemoCollectionResource extends BaseResourceCollection
{
}
```

### 请求校验

```app/Http/Requests/DemoRequest.php
use ZhenMu\LaravelInitTemplate\Http\Requests\BaseFormRequest;

class DemoRequest extends BaseFormRequest
{
}
```

### 模型

```app/Models/User.php
<?php

namespace App\Models;

class User extends \ZhenMu\LaravelInitTemplate\Models\User
{
}

```

- 其他模型

```app/Models/Demo.php
<?php

namespace ZhenMu\LaravelInitTemplate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Demo extends BaseModel
{
    use HasFactory;
}

```

### jwt 登录

- 模型配置，引入 JwtUserTrait;

```app/Models/Admin.php
use ZhenMu\LaravelInitTemplate\Traits\JwtUserTrait;

class Admin extends BaseModel
{
    use JwtUserTrait;
}

```

- 控制器配置，引入 JwtLoginControllerTrait;

```app/Http/Controllers/AuthController.php
use ZhenMu\LaravelInitTemplate\Traits\JwtLoginControllerTrait;

class AuthController extends Controller
{
    use JwtLoginControllerTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login']]);
    }
}

```

- 路由配置，添加登录相关路由

```api.php
Route::prefix('auth')->middleware('auth')->group(function () {
    // 用户登录管理
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
```

### 部署

```shell
git subtree add -P deploy/docker/ git@codeup.aliyun.com:5f10fd25d2481460393375d0/base/deploy/deploy-docker.git master
```

## License

MIT