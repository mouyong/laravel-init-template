<?php

namespace ZhenMu\LaravelInitTemplate\Traits;

use Illuminate\Http\Response;

trait ResponseTrait
{
    public function string2utf8($string = '')
    {
        if (empty($string)) {
            return $string;
        }

        $encoding_list = [
            "ASCII",'UTF-8',"GB2312","GBK",'BIG5'
        ];

        $encode = mb_detect_encoding($string, $encoding_list);

        $string = mb_convert_encoding($string, 'UTF-8', $encode);

        return $string;
    }

    public function success($data = [], $err_msg = 'success', $err_code = 200, $headers = [])
    {
        if (is_string($data)) {
            $err_code = $err_msg;
            $err_msg = $data;
            $data = [];
        }

        // 处理 meta 数据
        $meta = [];
        if (isset($data['data']) && isset($data['meta'])) {
            extract($data);
        }

        $err_msg = $this->string2utf8($err_msg);

        if ($err_code === 200 && ($config_err_code = config('laravel-init-template.response.err_code', 200)) !== $err_code) {
            $err_code = $config_err_code;
        }

        $res = compact('err_code', 'err_msg', 'data') + array_filter(compact('meta'));

        return response()->json(
            $res,
            Response::HTTP_OK,
            array_merge([
                'Access-Control-Allow-Origin' => '*'
            ], $headers),
            \JSON_UNESCAPED_SLASHES|\JSON_UNESCAPED_UNICODE
        );
    }

    public function fail($err_msg = 'unknown error', $err_code = 400, $data = []) {
        return $this->success($data, $err_msg ?: 'unknown error', $err_code ?: 500);
    }

    public function reportableHandle()
    {
        return function (\Throwable $e) {
            //
        };
    }

    public function renderableHandle()
    {
        return function (\Throwable $e) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return $this->fail('登录失败，请稍后重试', $e->getCode() ?: config('laravel-init-template.auth.unauthorize_code', 401));
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return $this->fail(head(head($e->errors())), $e->status);
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return $this->fail('404 Data Not Found.', $e->getStatusCode());
            }

            logger('error', [
                'class' => get_class($e),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file_line' => sprintf('%s:%s', $e->getFile(), $e->getLine()),
            ]);

            return $this->fail($e->getMessage(), $e->getCode());
        };
    }
}