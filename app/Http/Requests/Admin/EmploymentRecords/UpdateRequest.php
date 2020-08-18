<?php

namespace App\Http\Requests\Admin\EmploymentRecords;

use App\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            //简单履历id
            'resume_id' => [
                'integer',
            ],
            //応募求人ids
            'job_ids' => [
                'max:255',
            ],
            //操作id
            'admin_id' => [
                'integer',
            ],
        ];
    }

    public function attributes()
    {
        return [
            //
        ];
    }
}
