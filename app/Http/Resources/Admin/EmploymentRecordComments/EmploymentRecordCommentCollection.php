<?php

namespace App\Http\Resources\Admin\EmploymentRecordComments;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmploymentRecordCommentCollection extends ResourceCollection
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
            'data' => EmploymentRecordCommentResource::collection($this->collection),
            'count' => $this->resource->total(),
        ];
    }
}
