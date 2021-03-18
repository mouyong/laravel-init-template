<?php

namespace ZhenMu\LaravelInitTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login']]);
    }

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

        $user = User::query()->where('name', $credentials['name'])->first();

        throw_if(is_null($user), UnauthorizedException::class, '登录失败，账号不存在', 401);
        throw_if(! $token = auth()->attempt($credentials), UnauthorizedException::class, '登录失败，密码错误', 401);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->success(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
        throw_if(empty(auth()->user()->tenant), UnauthorizedException::class, '未获取到租户信息');

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'tenant_id' => auth()->user()->tenant->id,
        ]);
    }
}