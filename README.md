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

- 配置 `JWT_SECRET`、`JWT_TTL`

```
php artisan jwt:secret

JWT_SECRET=
# minutes of 3 day
JWT_TTL=4320
```

- 守卫配置 `config/auth.php`

```config/auth.php
'guards' => [
    'jwt-api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

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

### 文件上传七牛

- 添加配置文件

```filesystems.php
return [
   'disks' => [
        //...
        'qiniu' => [
            'driver'  => 'qiniu',
            'domains' => [
                'default'   => env('QINIU_DOMAIN', 'xxxxx.com1.z0.glb.clouddn.com'), //你的七牛域名
                'https'     => env('QINIU_DOMAIN', 'dn-yourdomain.qbox.me'),         //你的HTTPS域名
                'custom'    => env('QINIU_DOMAIN', 'static.abc.com'),                //Useless 没啥用，请直接使用上面的 default 项
            ],
            'access_key'=> env('QINIU_ACCESS_KEY', 'xxxxxxxxxxxxxxxx'),  //AccessKey
            'secret_key'=> env('QINIU_SECRET_KEY', 'xxxxxxxxxxxxxxxx'),  //SecretKey
            'bucket'    => env('QINIU_BUCKET', 'xxxxxxxxxxxxxxxx'),  //Bucket名字
            'notify_url'=> env('QINIU_NOTIFY_URL', 'xxxxxxxxxxxxxxxx'),  //持久化处理回调地址
            'access'    => env('QINIU_ACCESS', 'public'),  //空间访问控制 public 或 private
//            'hotlink_prevention_key' => 'afc89ff8bd2axxxxxxxxxxxxxxbb', // CDN 时间戳防盗链的 key。 设置为 null 则不启用本功能。
//            'hotlink_prevention_key' => 'cbab68a279xxxxxxxxxxab509a', // 同上，备用
        ],
        //...
    ]
];
```

- 配置环境变量

```
# 七牛
QINIU_ACCESS_KEY=
QINIU_SECRET_KEY=
QINIU_BUCKET=
QINIU_DOMAIN=https://your.domain.com
```


### 部署

```shell
git subtree add -P deploy/docker/ https://github.com/mouyong/docker-php.git master

# swoole 构建
cp deploy/docker/swoole/Dockerfile .
# 环境变量
cp deploy/docker/acm.sh .
# 定时任务
cp deploy/docker/crontab .

COMPOSER_MEMORY_LIMIT=-1 composer require swooletw/laravel-swoole -vvv

php artisan vendor:publish --tag=laravel-swoole
```

更新 `config/swoole_http.php` 配置文件
```diff
-    'host' => env('SWOOLE_HTTP_HOST', '127.0.0.1'),
-    'port' => env('SWOOLE_HTTP_PORT', '1215'),
+    'host' => env('SWOOLE_HTTP_HOST', '0.0.0.0'),
+    'port' => env('SWOOLE_HTTP_PORT', '80'),

    'instances' => [
-       //
+        'auth',
    ],

    'providers' => [
        Illuminate\Pagination\PaginationServiceProvider::class,
+        Dcat\Admin\AdminServiceProvider::class, // dact-admin
    ],
```

## License

MIT