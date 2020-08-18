<?php

namespace App\Http\Requests\Web\Experiences;

use App\Http\Requests\FormRequest;

class StoreVideoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'video_url' => [
                'required',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'video_url' => ' 自己紹介映像',
        ];
    }
}
