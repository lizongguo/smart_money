<?php

namespace App\Http\Resources\Admin\EnterpriseEmploymentMemos;

use Illuminate\Http\Resources\Json\JsonResource;

class EnterpriseEmploymentMemoResource extends JsonResource
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

        if ($this->record && $this->record->company) {
            $data['company_name'] = $this->record->company->company_name;
        } else {
            $data['company_name'] = '';
        }
        if ($this->record && $this->record->resume) {
            $data['name'] = $this->record->resume->name;
        } else {
            $data['name'] = '';
        }

        $date = date('Y-m-d');
        $createdAt = date('Y-m-d', strtotime($data['created_at']));
        $data['is_edit'] = $date==$createdAt;

        return $data;
    }
}
