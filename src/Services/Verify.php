<?php

namespace ZhenMu\LaravelInitTemplate\Services;

use Carbon\Carbon;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\PhoneNumber;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class Verify
{
    const VERIFY_TYPE = [
        'user' => 'user:verify:code:',
    ];

    /**
     * 短信验证码
     *
     * @param string $mobile
     * @param string $type
     * @param string|null $mobileCode
     *
     * @return bool|int[]|mixed
     *
     * @throws \RuntimeException
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function sendCode(string $mobile, string $type, string $mobileCode = null)
    {
        $cacheKey = self::VERIFY_TYPE[$type].$mobileCode.$mobile;

        if (($cache = Cache::get($cacheKey)) && Carbon::now()->diffInSeconds($cache['time']) < 60) {
            throw new \RuntimeException('发送验证码频繁，请稍后尝试');
        }

        if (!app()->environment('production')) {
            $gateways = ['errorlog'];
        } else {
            $gateways = ['chuanglan'];
        }

        $code = app()->environment('production') ? rand(1000, 9999) : '0000';
        if (config('laravel-init-template.sms.code_debug') && \request()->get('mock')) {
            $code = config('laravel-init-template.sms.code_debug');
            $gateways = ['errorlog'];
        }

        Cache::put($cacheKey, [
            'code' => $code,
            'time' => $time = Carbon::now()->toDateTimeString(),
        ], after_minutes(5));
        \info('发送验证码', [
            'mobile' => $mobile,
            'code' => $code,
            'time' => $time,
        ]);

        $easySms = new EasySms(config('easysms'));

        try {
            if ($mobileCode) {
                $mobile = new PhoneNumber($mobile, $mobileCode);
            }

            $result = $easySms->send($mobile, [
                'content' => sprintf('您的验证码为 %s，请于 5分钟内完成验证，若非本人操作，请忽略本短信', $code),
                'sign' => sprintf('【%s】', config('easysms.gateways.chuanglan.sign')),
            ], $gateways);

            if ($gateways && $result[head($gateways)]['status'] === 'success') {
                return true;
            }
        } catch (NoGatewayAvailableException $exception) {
            $data = \json_decode($exception->getLastException()->getMessage(), true);

            throw new \RuntimeException($data['errorMsg'], $data['code']);
        }
    }

    /**
     * 验证验证码是否正确
     *
     * @param string $type
     * @param string $mobile
     * @param string $code
     *
     * @param string|null $mobileCode
     * @return bool
     *
     */
    public function validate(string $type, string $mobile, string $code, string $mobileCode = null)
    {
        $cacheKey = self::VERIFY_TYPE[$type].$mobileCode.$mobile;

        $cache = Cache::get($cacheKey);
        \info('使用验证码', [
            'mobile' => $mobile,
            'cache' => $cache,
            'time' => Carbon::now()->toDateTimeString(),
        ]);

        if (!$cache) {
            throw new \RuntimeException('验证码已过期或不存在');
        }

        if ($cache['code'] != $code) {
            Cache::increment($cacheKey.'error_num', 1);

            if (Cache::get($cacheKey.'error_num') >= 3) {
                Cache::delete($cacheKey);
                Cache::delete($cacheKey.'error_num');
            }

            throw new \RuntimeException('验证码不正确');
        }

        Cache::delete($cacheKey.'error_num');
        Cache::delete($cacheKey);

        return true;
    }

    public function getCode(string $mobile, string $type, string $mobileCode = null)
    {
        $cacheKey = self::VERIFY_TYPE[$type].$mobileCode.$mobile;

        return Cache::get($cacheKey);
    }
}
