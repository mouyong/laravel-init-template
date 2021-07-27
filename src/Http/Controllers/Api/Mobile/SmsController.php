<?php

namespace App\Http\Controllers\Api\Mobile\Wechat;

use ZhenMu\LaravelInitTemplate\Http\Controllers\BaseController;
use ZhenMu\LaravelInitTemplate\Services\Verify;

class SmsController extends BaseController
{
    public function sendVerifySms(Verify $verify)
    {
        \request()->validate([
            'mobile' => 'required',
            'mobile_code' => ['nullable', 'string'],
        ], [
            'mobile.required' => '手机号码不能为空',
        ]);

        $sendResult = $verify->sendCode(
            \request()->get('mobile'),
            \request()->get('type') ?? 'user',
            \request()->get('mobile_code')
        );

        $code = $verify->getCode(
            \request()->get('mobile'),
            \request()->get('type') ?? 'user',
            \request()->get('mobile_code'));

        $codeResult = [];
        if (config('laravel-init-template.sms.code_debug')) {
            $codeResult = \request()->get('mock') ? ['code' => $code] : [];
        }

        return $this->success([
            'send_result' => $sendResult,
        ] + $codeResult);
    }
}
