<?php

namespace App\Http\Requests\Admin\EnterpriseEmploymentRecords;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            //企业id
            'company_id' => [
                'integer',
            ],
            //企业名称
            'resume_id' => [
                'integer',
            ],
            //ステータス
            'status' => [
                'integer',
            ],
            //创建者id
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
