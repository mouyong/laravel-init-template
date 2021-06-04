<?php

namespace ZhenMu\LaravelInitTemplate\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use ZhenMu\Support\Traits\ResponseTrait;

class BaseResource extends JsonResource
{
    use ResponseTrait;

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return $this->success($this->toArray($request));
    }
}
