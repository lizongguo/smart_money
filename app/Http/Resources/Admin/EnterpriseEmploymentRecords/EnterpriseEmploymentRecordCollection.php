<?php

namespace App\Http\Resources\Admin\EnterpriseEmploymentRecords;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EnterpriseEmploymentRecordCollection extends ResourceCollection
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
            'data' => EnterpriseEmploymentRecordResource::collection($this->collection),
            'count' => $this->resource->total(),
        ];
    }
}
