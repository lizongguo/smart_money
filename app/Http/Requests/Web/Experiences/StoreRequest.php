<?php

namespace App\Http\Requests\Web\Experiences;

use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:20',
            ],
            'name_kana' => [
                'required',
                'max:20',
            ],
            'birthday' => [
                'required',
                'date',
                'before:'.date('Y-m-d')
            ],
            'sex' => [
                'required',
                'in:1,2',
            ],
            'nationality_id' => [ //国籍(地域)
                'required',
            ],
            'nationality' => [
                'required_if:nationality_id,17',
            ],
            'address' => [ //現住所
                'required',
                'in:1,2',
            ],
            'address_id' => [
                'required_if:address,1',
            ],
            'address_other' => [
                'nullable',
                'string',
                'max:100',
            ],
            'address_kana' => [
                'required',
                'string',
                'max:100',
            ],
            'postal_code' => [
                'required_if:address,1',
                'regex:#^[\d]{7}$#',
            ],
            'cell_phone' => [
                'required',
                'regex:#^^[\\d|\\+|\\-|\\(|\\)]{7,20}$#i',
            ],
            'emergency_address_id' => [
                'required_if:emergency_contact,0',
            ],
            'emergency_address_other' => [
                'sometimes',
                'required_if:emergency_contact,0',
                'string',
                'max:100',
            ],
            'emergency_address_kana' => [
                'required_if:emergency_contact,0',
                'string',
                'max:100',
            ],
            'emergency_postal_code' => [
                'required_if:emergency_contact,0',
                'regex:#^[\d]{7}$#i',
            ],
            'emergency_cell_phone' => [
                'required_if:emergency_contact,0',
                'regex:#^^[\\d|\\+|\\-|\\(|\\)]{7,20}$#i',
            ],
            'nearest_station' => [
                'sometimes',
                'max:20',
            ],
            'email' => [
                'required',
                'email',
            ],
            'residence_in_japan_year' => [
                'required',
            ],
            'residence_in_japan_month' => [
                'required',
            ],
            /*'photo' => [
                'required',
            ],*/
            'visa_type' => [
                'required_if:address,1',
            ],
            'visa_other' => [
                'required_if:visa_type,7',
            ],
            'visa_term' => [
                'sometimes',
                'date',
                'after:' . date('Y-m-d')
            ],
            'academic_background.*.country' => [
                'required',
                'max:20'
            ],
            'academic_background.*.final_education' => [
                'required',
            ],
            'academic_background.*.enrol_date_start' => [
                'required',
                'date',
                'before:'.date('Y-m-d')
            ],
            'academic_background.*.enrol_date_end' => [
                'required',
                'date',
                'after:academic_background.*.enrol_date_start'
            ],
            'academic_background.*.school_name' => [
                'required',
                'max:50'
            ],
            'academic_background.*.department_name' => [
                'sometimes',
                'max:50'
            ],
            'academic_background.*.subject_name' => [
                'sometimes',
                'max:50'
            ],
            /*'academic_background.*.science_art' => [
                'required',
                'in:1,2'
            ],*/
            'is_qualification_and_license' => [
                'required',
                'in:0,1'
            ],
            'qualification_and_license.*.name' => [
                'sometimes',
            ],
            'qualification_and_license.*.level' => [
                'sometimes',
                'required_if:qualification_and_license.*.name,1'
            ],
            'qualification_and_license.*.point' => [
                'sometimes',
                'required_if:qualification_and_license.*.name,2,3,4',
                'regex:#[\d]{0,3}#',
            ],
            'qualification_and_license.*.certificate_other' => [
                'sometimes',
                'required_if:qualification_and_license.*.name,4',
                'max:20',
            ],
            'qualification_and_license.*.certificate_date' => [
                'sometimes',
                'date',
                'before:'.date('Y-m-d')
            ],

            'jp_level' => [
                'required',
            ],
//            'en_level' => [
//                'required',
//            ],
            'language_skill.*.other_level' => [
                'sometimes',
            ],
            'language_skill.*.other_text' => [
                'sometimes',
                'max:40',
            ],
            'it_skill_os' => [
                'sometimes',
                'array',
//                'min:1',
            ],
            'it_skill_office' => [
                'sometimes',
                'array',
//                'min:1',
            ],

            'it_skill_graphic.*.name' => [
                'sometimes',
            ],
            'it_skill_graphic.*.other' => [
                'sometimes',
                'required_if:it_skill_graphic.*.name,その他',
                'max:50'
            ],
            'it_skill_graphic.*.year' => [
                'sometimes',
            ],
            'it_skill_graphic.*.month' => [
                'sometimes',
            ],
            'it_skill_language.*.name' => [
                'sometimes',
            ],
            'it_skill_language.*.other' => [
                'sometimes',
                'required_if:it_skill_language.*.name,その他',
                'max:50'
            ],
            'it_skill_language.*.year' => [
                'sometimes',
            ],
            'it_skill_language.*.month' => [
                'sometimes',
            ],
            'it_skill_db.*.name' => [
                'sometimes',
            ],
            'it_skill_db.*.other' => [
                'sometimes',
                'required_if:it_skill_db.*.name,その他',
                'max:50'
            ],
            'it_skill_db.*.year' => [
                'sometimes',
            ],
            'it_skill_db.*.month' => [
                'sometimes',
            ],

            'it_skill_framework.*.name' => [
                'sometimes',
            ],
            'it_skill_framework.*.other' => [
                'sometimes',
                'required_if:it_skill_framework.*.name,その他',
                'max:50'
            ],
            'it_skill_framework.*.year' => [
                'sometimes',
            ],
            'it_skill_framework.*.month' => [
                'sometimes',
            ],
            'commuting_hours' => [
                'required'
            ],
            'family_members_num' => [
                'required',
            ],

            'is_spouse' => [
                'required',
                'in:0,1',
            ],
            'is_spouse_support' => [
                'required',
                'in:0,1',
            ],
            'desired_place' => [
                'required',
                'array',
                'min:1',
            ],
//            'desired_place_ids' => [
//                'required',
//            ],
            'pr_other' => [
                'required',
                "max:500",
            ],
            'other_expected_types' => [
                'sometimes',
                "max:500",
            ],
            'is_experience' => [
                'required',
                "in:0,1",
            ],
            'job_summary' => [
                "max:500",
            ],
            'experiences.*.country' => [
                'required_if:is_experience,1',
                'max:20'
            ],
            'experiences.*.start_date' => [
                'required_if:is_experience,1',
                'date',
                'before:'.date('Y-m-d')
            ],
            'experiences.*.end_date' => [
                'required_if:experiences.*.is_now,0',
                'date:experiences.*.is_now,0',
                'after:experiences.*.start_date'
            ],
            'experiences.*.corporate_name' => [
                'required_if:is_experience,1',
                'max:100'
            ],
            'experiences.*.post_name' => [
                'sometimes',
                'max:100'
            ],

            'experiences.*.industry_name' => [
                'sometimes',
            ],
            'experiences.*.industry_other' => [
                'sometimes',
                'required_if:experiences.*.industry_name,その他',
            ],
            'experiences.*.employees_num' => [
                'sometimes',
//                'integer',
//                'between:1,500000'
            ],
            'experiences.*.employ_type' => [
                'required_if:is_experience,1',
            ],
            'experiences.*.occupation' =>[
                'sometimes',
            ],
            'experiences.*.occupation_other' => [
                'sometimes',
                'required_if:experiences.*.occupation,その他',
                'max:20',
            ],
            'experiences.*.annual_income' => [
                'required_if:is_experience,1',
            ],
            'experiences.*.undertake_business' =>[
                'required_if:is_experience,1',
                'max:2000'
            ],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '氏名',
            'name_kana' => 'ふりがな',
            'cover_img' => '生年月日',
            'user_id' => '用户id',
            'birthday' => '生年月日',
            'sex' => '性別',
            'nationality_id' => '国籍(地域)',
            'nationality' => '国籍(地域)その他',
            'address' => '現住所',
            'address_id' => '住所',
            'address_other' => '住所',
            'address_kana' => 'ふりがな',
            'postal_code' => '郵便番号',
            'cell_phone' => '電話',
            'out_cell_phone' => '電話',
            'emergency_contact' => '緊急連絡先',
            'emergency_address_id' => '緊急連絡先-住所',
            'emergency_address_other' => '緊急連絡先-住所',
            'emergency_address_kana' => '緊急連絡先-ふりがな',
            'emergency_postal_code' => '緊急連絡先-郵便番号',
            'emergency_cell_phone' => '緊急連絡先-電話',
            'nearest_station' => '最寄駅',
            'email' => 'Eメール',
            'residence_in_japan_year' => '来日年数',
            'residence_in_japan_month' => '来日年数',
            'photo' => '顔写真',
            'visa_type' => '在留資格',
            'visa_other' => '在留資格',
            'visa_term' => 'ビザ有効期限',
            'academic_background.*.country' => '学歴(国籍・地域)',
            'academic_background.*.final_education' => '学歴(学校区分)',
            'academic_background.*.enrol_date_start' => '学歴(入学開始時間)',
            'academic_background.*.enrol_date_end' => '学歴(入学終了時間)',
            'academic_background.*.school_name' => '学歴(学校名)',
            'academic_background.*.department_name' => '学歴(研究科名)',
            'academic_background.*.subject_name' => '学歴(専攻名)',
            'academic_background.*.science_art' => '学歴(文理区分)',
            'is_qualification_and_license' => '資格・免許',

            'qualification_and_license.*.name' => '資格・免許（名称）',
            'qualification_and_license.*.level' => '資格・免許（LEVEL）',
            'qualification_and_license.*.point' => '資格・免許（点）',
            'qualification_and_license.*.certificate_other' =>  '資格・免許（名称）',
            'qualification_and_license.*.certificate_date' =>  '資格・免許（取得年月）',
            'jp_level' =>  '語学スキル（日本語）',
            'en_level' =>  '語学スキル（英語）',
            'language_skill.*.other_level' =>  '語学スキル（その他）',
            'language_skill.*.other_text' =>  '語学スキル（その他）',
            'it_skill_os' =>  'OS',
            'it_skill_office' => "Office",
            'it_skill_graphic.*.name' => "デザイン(2D/3D)（名称）",
            'it_skill_graphic.*.other' => "デザイン(2D/3D)（その他）",
            'it_skill_graphic.*.year' => "デザイン(2D/3D)（使用経験）",
            'it_skill_graphic.*.month' => "デザイン(2D/3D)（使用経験）",
            'it_skill_language.*.name' => "開発言語（名称）",
            'it_skill_language.*.other' => "開発言語（その他）",
            'it_skill_language.*.year' => "開発言語（使用経験）",
            'it_skill_language.*.month' => "開発言語（使用経験）",
            'it_skill_db.*.name' => "DB（名称）",
            'it_skill_db.*.other' => "DB（その他）",
            'it_skill_db.*.year' => "DB（使用経験）",
            'it_skill_db.*.month' => "DB（使用経験）",
            'it_skill_framework.*.name' => "フレームワーク（名称）",
            'it_skill_framework.*.other' => "フレームワーク（その他）",
            'it_skill_framework.*.year' => "フレームワーク（使用経験）",
            'it_skill_framework.*.month' => "フレームワーク（使用経験）",
            'commuting_hours' => '通勤時間',
            'family_members_num' => '扶養家族（配偶者を除く）',
            'is_spouse' =>  '配偶者',
            'is_spouse_support' => '配偶者の扶養義務',
            'desired_place' => '希望勤務地',
            'desired_place_ids' => '希望勤務地-都道府県',
            'pr_other' => '志望の動機、自己PR、趣味、特技など',
            'other_expected_types' => '本人希望記入欄',
            'is_experience' => '職歴',
            'job_summary' => '職務要約',
            'experiences.*.country' => '職歴（国籍・地域）',
            'experiences.*.start_date' => '職歴（開始時間）',
            'experiences.*.end_date' => '職歴（終了時間）',
            'experiences.*.is_now' => '職歴（終了時間）',
            'experiences.*.corporate_name' => '職歴（会社名）',
            'experiences.*.post_name' => '職歴（部署/役職）',
            'experiences.*.industry_name' => '職歴（業種）',
            'experiences.*.industry_other' => '職歴（業種）',
            'experiences.*.employees_num' => '職歴（従業員数）',
            'experiences.*.employ_type' => '職歴（雇用形態）',
            'experiences.*.occupation' => '職歴（職種）',
            'experiences.*.occupation_other' => '職歴（職種-その他）',
            'experiences.*.annual_income' => '職歴（年収）',
            'experiences.*.undertake_business' =>'職歴（担当業務）',
        ];
    }

    public function messages()
    {
        return [
            'experiences.*.end_date.required_if' => '退社年月をご記入ください。',
            'visa_type.required_if' => '在留資格は空にできません。',
            'visa_term.required_if' => 'ビザ有効期限は空にできません。',
            'photo.required' => '顔写真をアップロードしてください。',
            'academic_background.*.enrol_date_start.required' => '入学年月をご記入ください。',
            'academic_background.*.enrol_date_end.required' => '卒業(卒業見込み)年月をご記入ください。',
            'desired_place.required' => 'ご選択ください。',
            'experiences.*.start_date.required_if' => '入社年月をご記入ください。',
            'experiences.*.undertake_business' => '担当業務は2000文字以内ご記入ください。',
            'email.email' => '正しいメールアドレスをご入力ください。',
        ];
    }
}
