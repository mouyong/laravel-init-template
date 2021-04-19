<?php

namespace ZhenMu\LaravelInitTemplate\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use ZhenMu\LaravelInitTemplate\Traits\ResponseTrait;

class BaseResourceCollection extends ResourceCollection
{
    use ResponseTrait;

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        $params = [$this->toArray($request)];

        if ($this->resource instanceof AbstractPaginator) {
            $item = [
                    'current_page' => $this->currentPage(),
                    'per_page' => $this->perPage()
                ] + ($this->resource instanceof LengthAwarePaginator ? ['total' => $this->total()] : []);

            array_push($params, $item);
        }

        list($data, $meta) = $params;

        return $this->success([
            'data' => $data,
            'meta' => $meta,
        ]);
    }
}
