<?php

namespace App\Http\Requests\Admin\EmploymentRecordComments;

use App\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            //求職者管理记录id
            'employment_record_id' => [
                'required',
                'integer',
                'exists:employment_records,id'
            ],
            //コメント
            'content' => [
                'required',
                'max:255',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'employment_record_id' => '求職者',
            'content' => 'コメント',
        ];
    }
}
