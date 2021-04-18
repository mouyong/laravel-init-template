<?php

namespace Tests\Traits;

use Cblink\YApiDoc\YApi;
use Cblink\YApiDoc\YapiDTO;
use Illuminate\Testing\TestResponse;

trait UploadYapiDocTrait
{
    use RequestTrait;

    /**
     * 验证接口是否请求成功，并且与接口返回结构保持一致
     *
     * @param TestResponse $response
     * @param array $struct
     * @return TestResponse
     */
    protected function assertSuccess(TestResponse $response, array $struct = [])
    {
        $response->assertStatus(200)->assertJson(['err_code' => 0]);

        // 如果需要验证结构体，并且不是列表数据的结构体
        if ($struct && !empty($response->getContent())) {
            $response->assertJsonStructure([
                'data' => $struct
            ]);
        }

        return $response;
    }


    /**
     * @param $response
     * @param YapiDTO $dto
     * @return YApi
     */
    public function yapi($response, YapiDTO $dto)
    {
        $this->app['config']->set('yapi', require base_path('tests/Yapi/yapi.php'));

        return new YApi($this->app['request'], $response, $dto);
    }
}