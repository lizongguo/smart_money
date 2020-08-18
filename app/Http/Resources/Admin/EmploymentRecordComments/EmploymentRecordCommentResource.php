<?php

namespace App\Http\Resources\Admin\EmploymentRecordComments;

use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentRecordCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $date = date('Y-m-d');
        $createdAt = date('Y-m-d', strtotime($data['created_at']));
        $data['is_edit'] = $date==$createdAt;

        return $data;
    }
}
