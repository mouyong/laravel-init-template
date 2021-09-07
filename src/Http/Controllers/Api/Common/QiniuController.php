<?php

namespace ZhenMu\LaravelInitTemplate\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use zgldh\QiniuStorage\QiniuStorage;

class QiniuController extends Controller
{
    public function token()
    {
        \request()->validate([
            'name' => ['nullable', 'string'],
            'expired_time' => ['nullable', 'integer', 'max:3600'],
        ]);

        $qiniu = QiniuStorage::disk('qiniu');

        $token = $qiniu->uploadToken(\request()->get('name'), \request()->get('expired_time', 3600));

        return $this->success([
            'token' => $token,
            'domain' => config('filesystems.disks.qiniu.domains.default'),
        ]);
    }
}
