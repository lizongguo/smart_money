<?php

namespace App\Http\Requests\Admin\EnterpriseEmploymentMemos;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            //求职记录id
            'enterprise_employment_record_id' => [
                'required',
                'integer',
            ],
            //memo记录
            'memo' => [
                'required',
                'max:255',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'enterprise_employment_record_id' => '企業求職者',
            'memo' => 'メモ',
        ];
    }
}
