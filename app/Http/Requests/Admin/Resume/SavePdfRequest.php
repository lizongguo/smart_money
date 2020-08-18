<?php

namespace App\Http\Requests\Admin\Resume;

use App\Http\Requests\FormRequest;

class SavePdfRequest extends FormRequest
{
    public function rules()
    {
        return [
            //履歴PDF
            'pdf_url' => [
                'required',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'pdf_url' => '履歴PDF'
        ];
    }
}
