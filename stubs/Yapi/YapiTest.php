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

        Artisan::call('upload:yapi');

        dump("\n" . Artisan::output());

        $this->assertTrue(true);
    }

}
