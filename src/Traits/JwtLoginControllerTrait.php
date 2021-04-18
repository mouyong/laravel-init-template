<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

use App\Models\User;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

trait JwtLoginControllerTrait
{
    protected $model = User::class;

    protected $loginField = 'name';

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $data = request()->validate([
            'mobile' => 'required',
            'password' => 'required',
        ], [
            'mobile.required' => '用户名必填', // 前端传过来的字段是这个，懒得修改，直接用前端传的字段
        ]);

        $credentials['name'] = $data['mobile'];
        $credentials['password'] = $data['password'];

        $user = $this->model::query()->where($this->model::username($this->loginField), $credentials['name'])->first();

        throw_if(is_null($user), UnauthorizedException::class, '登录失败，账号不存在', 401);
        throw_if(! $token = $this->guard()->attempt($credentials), UnauthorizedException::class, '登录失败，密码错误', 401);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->success(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        throw_if(empty($this->guard()->user()->tenant), UnauthorizedException::class, '未获取到租户信息');

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard|GuardHelpers
     */
    protected function guard($guard = null)
    {
        return Auth::guard($guard ?? config('auth.defaults.guard'));
    }
}