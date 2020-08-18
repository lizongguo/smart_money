<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => ':attributeを受け入れる必要があります。',
    'active_url'      => ':attribute は有効なURLではありません。',
    'after'           => '正しい年月をご記入ください。',
    'after_or_equal'  => '正しい生年月日をご記入ください。',
    'alpha'           => ':attribute はA-Zのみで構成できます。',
    'alpha_dash'      => ':attribute は、文字、数字、ダッシュ（-）、および下線（_）のみで構成できます。',
    'alpha_num'       => ':attribute は文字と数字のみで構成できます。',
    'array'           => ':attribute は配列でなければなりません。',
    'before'          => '正しい生年月日をご記入ください。',
    'before_or_equal' => '正しい生年月日をご記入ください。',
    'between'         => [
        'numeric' => ':attribute は :min - :max の間でなければなりません。',
        'file'    => ':attribute は :min - :max KBである必要があります。',
        'string'  => ':attribute は :min - :max 文字の間でなければなりません。',
        'array'   => ':attribute には :min - :max 要素のみが必要です。',
    ],
    'boolean'        => ':attribute はブール値でなければなりません。',
    'confirmed'      => ':attribute 入力が2回矛盾しています。',
    'date'           => '正しい生年月日をご記入ください。',
    'date_equals'    => '正しい生年月日をご記入ください。',
    'date_format'    => '正しい生年月日をご記入ください。',
    'different'      => ':attribute と :other は異なる必要があります。',
    'digits'         => ':attribute 必须是 :digits 位的数字。',
    'digits_between' => ':attribute 必须是介于 :min 和 :max 位的数字。',
    'dimensions'     => ':attribute 图片尺寸不正确。',
    'distinct'       => ':attribute は既に存在します。',
    'email'          => ':attribute は有効なメールボックスではありません。',
    'ends_with'      => ':attribute 必须以 :values 为结尾。',
    'exists'         => ':attribute は存在しません。',
    'file'           => ':attribute はファイルでなければなりません。',
    'filled'         => ':attribute は空にできません。',
    'gt'             => [
        'numeric' => ':attribute 必须大于 :value。',
        'file'    => ':attribute 必须大于 :value KB。',
        'string'  => ':attribute 必须多于 :value 个字符。',
        'array'   => ':attribute 必须多于 :value 个元素。',
    ],
    'gte' => [
        'numeric' => ':attribute 必须大于或等于 :value。',
        'file'    => ':attribute 必须大于或等于 :value KB。',
        'string'  => ':attribute 必须多于或等于 :value 个字符。',
        'array'   => ':attribute 必须多于或等于 :value 个元素。',
    ],
    'image'    => ':attribute 必须是图片。',
    'in'       => '選択した属性 :attribute は無効です。',
    'in_array' => ':attribute 没有在 :other 中。',
    'integer'  => ':attribute は整数でなければなりません。',
    'ip'       => ':attribute 必须是有效的 IP 地址。',
    'ipv4'     => ':attribute 必须是有效的 IPv4 地址。',
    'ipv6'     => ':attribute 必须是有效的 IPv6 地址。',
    'json'     => ':attribute 必须是正确的 JSON 格式。',
    'lt'       => [
        'numeric' => ':attribute 必须小于 :value。',
        'file'    => ':attribute 必须小于 :value KB。',
        'string'  => ':attribute 必须少于 :value 个字符。',
        'array'   => ':attribute 必须少于 :value 个元素。',
    ],
    'lte' => [
        'numeric' => ':attribute 必须小于或等于 :value。',
        'file'    => ':attribute 必须小于或等于 :value KB。',
        'string'  => ':attribute 必须少于或等于 :value 个字符。',
        'array'   => ':attribute 必须少于或等于 :value 个元素。',
    ],
    'max' => [
        'numeric' => ':attribute は :max より大きくできません。',
        'file'    => ':attribute は :max KBより大きくすることはできません。',
        'string'  => ':attribute は :max 文字を超えることはできません。',
        'array'   => ':attribute には最大 :max 個の要素があります。',
    ],
    'mimes'     => ':attribute 必须是一个 :values 类型的文件。',
    'mimetypes' => ':attribute 必须是一个 :values 类型的文件。',
    'min'       => [
        'numeric' => ':attribute 必须大于等于 :min。',
        'file'    => ':attribute 大小不能小于 :min KB。',
        'string'  => ':attribute 至少为 :min 个字符。',
        'array'   => ':attribute 至少有 :min 个单元。',
    ],
    'not_in'               => '已选的属性 :attribute 非法。',
    'not_regex'            => ':attribute 的格式错误。',
    'numeric'              => ':attribute 必须是一个数字。',
    'password'             => '密码错误',
    'present'              => ':attribute 必须存在。',
    'regex'                => '正しい:attributeをご記入ください。',
    /*'required'             => ':attribute は空にできません。',*/
    'required'             => 'ご記入ください。',
    'required_photo'             => '顔写真をアップロードしてください。',
    'required_if'          => ':other が :value の場合、:attribute は空にできません。',
    'required_unless'      => ':other が :values でない場合 :attribute は空にできません。',
    'required_with'        => '当 :values 存在时 :attribute 不能为空。',
    'required_with_all'    => '当 :values 存在时 :attribute 不能为空。',
    'required_without'     => '当 :values 不存在时 :attribute 不能为空。',
    'required_without_all' => '当 :values 都不存在时 :attribute 不能为空。',
    'same'                 => ':attribute と :other は同じでなければなりません。',
    'size'                 => [
        'numeric' => ':attribute サイズは :sizeである必要があります。',
        'file'    => ':attribute サイズは :size KBである必要があります。',
        'string'  => ':attribute は :size 文字でなければなりません。',
        'array'   => ':attribute は :size 要素でなければなりません。',
    ],
    'starts_with' => ':attribute は :values で始まる必要があります。',
    'string'      => ':attribute は文字列でなければなりません。',
    'timezone'    => ':attribute は有効なタイムゾーン値である必要があります。',
    'unique'      => ':attribute はすでに存在します。',
    'uploaded'    => ':attribute のアップロードに失敗しました。',
    'url'         => ':attribute フォーマットが不正です。',
    'uuid'        => ':attribute は有効なUUIDでなければなりません。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
