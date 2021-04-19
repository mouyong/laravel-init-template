<?php

namespace Tests\Yapi;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class YapiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_call_upload_to_yapi()
    {
        $this->app['config']->set('yapi', require 'yapi.php');

        $code = Artisan::call('upload:yapi');

        if ($code === 0) {
            dump('上传成功');
        }

        if ($output = Artisan::output()) {
            dump($output);
        }

        $this->assertTrue(true);
    }
}
