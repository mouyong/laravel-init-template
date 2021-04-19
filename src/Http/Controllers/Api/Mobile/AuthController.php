<?php

namespace ZhenMu\LaravelInitTemplate\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
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