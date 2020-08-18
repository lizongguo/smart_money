<?php

namespace App\Http\Resources\Admin\EmploymentRecords;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmploymentRecordCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code' => 0,
            'msg' => 'success',
            'status' => 200,
            'data' => EmploymentRecordResource::collection($this->collection),
            'count' => $this->resource->total(),
        ];
    }
}
