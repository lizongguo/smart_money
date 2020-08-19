<?php

return [
    #性别
    'sex' => [
        '1' => '男',
        '2' => '女',
        '3' => '保密'
    ],
    'fund_type' => [
        '1' => '股票型基金',
        '2' => '综合性基金',
        '3' => '社保基金',
    ],
    //权限
    'found_role' => [
        '1' => '风险提示权限',
        '2' => '基金关系图权限',
        '3' => '公司关系图权限',
        '4' => '财务报表权限',
        '5' => '审计报告权限',
        '6' => '出资明细权限',
        '7' => '查看其它权限',
        '8' => '项目风险权限'
    ],

    //权限
    'found_type' => [
        '1' => '个人',
        '2' => '公司',
    ],

    //币种
    'found_currency' => [
        '1' => '人民币',
        '2' => '美元'
    ],

    //内容类型
    'content_type' => [
        '1' => '文件',
        '2' => '文本'
    ],

    //公司状态
    'project_state' => [
        '0' => '未上市',
        '1' => '上市',
        '2' => '倒闭',
        '3' => '退出'
    ],

    //底部内容类型
    'buttom_type' => [
        '1' => '基金',
        '2' => '政策法规',
        '3' => '税务',
    ],

    'state' => [
        '0' => '禁用',
        '1' => '公开',
    ],

    'upload' => [
        'photo' => [
            'attachment' => [
                'ext' => ['png', 'jpg', 'jpeg', 'bmp','gif'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'thumbnail' => array('h' => 384, 'w' => 512, 'type' => '1'),
                'url_path' => '/upload/',
            ],
        ],
        'resume' => [
            'attachment' => [
                'ext' => ['doc', 'pdf', 'docx'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'url_path' => '/upload/resume/',
            ],
            'attachment_1' => [
                'ext' => ['doc', 'pdf', 'docx'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'url_path' => '/upload/resume/',
            ],
        ],
        'adminFile' => [
            'attachment' => [
                'ext' => ['doc', 'pdf', 'docx','xls', 'xlsx','jpg', 'png'],
                'base' => public_path(),
                'size' => 10 * 1024 * 1024,
                'url_path' => '/upload/resume/',
            ],
        ],

        'projectFile' => [
            'attachment' => [
                'ext' => ['doc', 'docx'],
                'base' => public_path(),
                'size' => 10 * 1024 * 1024,
                'url_path' => '/upload/resume/',
            ],
        ],

        'video' => [
            'attachment' => [
                'ext' => ['webm', 'mp4', 'ogg', 'mov'],
                'base' => public_path(),
                'size' => 100 * 1024 * 1024,
                'url_path' => '/upload/',
            ],
        ],
        'news' => [
            'attachment' => [
                'ext' => ['png', 'jpg', 'jpeg', 'gif'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'thumbnail' => array('h' => 384, 'w' => 512, 'type' => '1'),
                'url_path' => '/upload/',
            ],
        ],
        'banner' => [
            'attachment' => [
                'ext' => ['png', 'jpg', 'jpeg', 'gif'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'thumbnail' => array('h' => 768, 'w' => 1024, 'type' => '1'),
                'url_path' => '/upload/',
            ]
        ],
        'shop' => [
            'attachment' => [
                'ext' => ['png', 'jpg', 'jpeg', 'gif'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'thumbnail' => array('h' => 300, 'w' => 300, 'type' => '1'),
                'url_path' => '/upload/',
            ]
        ],
        'goods' => [
            'attachment' => [
                'ext' => ['png', 'jpg', 'jpeg', 'gif'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'thumbnail' => array('h' => 150, 'w' => 200, 'type' => '1'),
                'url_path' => '/upload/',
                'pixel' => ['h' => 150, 'w' => 200], //上传图片转有效
            ]
        ],
        'shopShow' => [
            'attachment' => [
                'ext' => ['png', 'jpg', 'jpeg', 'gif', 'mp4'],
                'base' => public_path(),
                'size' => 50 * 1024 * 1024,
//                'thumbnail' => array('h' => 300, 'w' => 300, 'type' => '1'),
                'url_path' => '/upload/',
            ]
        ],
        'user' => [
            'attachment' => [
                'ext' => ['jpg', 'gif', 'png', 'jpeg'],
                'base' => public_path(),
                'size' => 10 * 1024 * 1024,
                'thumbnail' => array('h' => 200, 'w' => 200, 'type' => '1'),//type 为0按比例缩放，1按固定宽度缩放，2按固定高度缩放，3按固定宽高缩放
                'url_path' => '/upload/',
            ],
        ],
        'avatar' => [
            'attachment' => [
                'ext' => ['jpg', 'gif', 'png', 'jpeg'],
                'base' => public_path(),
                'size' => 2 * 1024 * 1024,
                'thumbnail' => array('h' => 200, 'w' => 200, 'type' => '1'),//type 为0按比例缩放，1按固定宽度缩放，2按固定高度缩放，3按固定宽高缩放
                'url_path' => '/upload/',
            ],
        ],
        'ad' => [
            'attachment' => [
                'ext' => ['jpg', 'gif', 'png', 'jpeg'],
                'base' => public_path(),
                'size' => 10 * 1024 * 1024,
                'thumbnail' => array('h' => 768, 'w' => 1280, 'type' => '1'),//type 为0按比例缩放，1按固定宽度缩放，2按固定高度缩放，3按固定宽高缩放
                'url_path' => '/upload/',
            ],
        ],
        'fileinput' => [
            'attachment' => [
                'ext' => ['jpg', 'gif', 'png', 'jpeg'],
                'base' => public_path(),
                'size' => 10 * 1024 * 1024,
                'thumbnail' => array('h' => 150, 'w' => 200, 'type' => '1'),//type 为0按比例缩放，1按固定宽度缩放，2按固定高度缩放，3按固定宽高缩放
                'url_path' => '/upload/',
                'pixel' => ['h' => 300, 'w' => 400], //上传图片转有效
            ],
        ],
    ],

    'alert_msg' => [
        'system' => [
            'error' => "未知のエラー。", //服务器数据库等发生的未知错误
        ],

        'experiences' => [
            'save_success' => "保存に成功しました。", //详细履历保存成功
            'save_failed' => "保存に失敗しました。", //详细履历保存失败

            'video_save_success' => "保存に成功しました。", //小视频保存成功
            'video_save_failed' => "保存に失敗しました。", //小视频保存失败

            'download_null' => "『履歴書・職務経歴書』を記入してからお試しください。", //下载pdf时还未做成履历书
            'download_error' => "PDFのダウンロードが失敗しました。暫くしてから再度お試しください。", //下载pdf失败
        ],

        'favorite' => [
            'favorite_success' => "気に入りに登録しました。", //收藏成功
            'favorite_cancel_success' => "気に入りから削除しました。", //取消收藏成功
        ],

        'JobRecord' => [
            'record_success' => "応募に成功しました。", //应募成功
            'memo_save_success' => "保存に成功しました。", //memo保存成功
            'status_save_success' => "送信に成功しました。", //应募时用户和企业对话消息发送成功

            'scout_success' => "スカウトに成功しました。", //scout成功
            'scout_overstep' => "1日スカウトできる人数の上限に達しました。", //企业超出每日scout数
        ],

        'job' => [
            'save_success' => "保存に成功しました。", //求人保存成功
            'save_failed' => "保存に失敗しました。", //求人保存失败

            'copy_success' => "コピーに成功しました。編集画面を開きますか？", //copy成功，是否要跳转copy求人的详细

            'delete_success' => "削除に成功しました。", //求人删除成功
            'delete_failed' => "削除に失敗しました。", //求人删除失败
        ],

        'account' => [
            'mail_send_success' => "パスワードリセットの手順を{mail}に送信しました。",  //找回密码时发送邮件成功
            'mail_required' => "用户信息不能为空",  //邮件输入为空
            'mail_regex' => "メールアドレスのフォーマットが正しくありません。",  //邮件输入格式错误
            'mail_not_exist' => "このメールアドレスが登録されていません。",  //找回密码时输入的邮件找不到对应的用户
            'mail_exist' => "このメールアドレスは既に登録済みです。",  //简单履历添加时 邮箱已存在
            'agent_resume_exist' => "この方は既に登録しました。ご不明点が有りましたら弊社担当までご連絡ください。",  //agent简单履历添加时 用户已存在

            'password_success' => "パスワードの変更に成功しました。",  //密码修改成功
            'password_required' => "请输入密码",  //修改密码时密码为空
            'password_regex' => "密码格式不正确",  //修改密码时密码格式错误6到12位应数字

            'login_error' => "账号不存在或密码错误",  //登录时密码错误
            'login_success' => "登录成功",  //登录成功

            'mail_success' => "メールアドレスの変更に成功しました。",  //个人资料邮箱修改成功

            'info_success' => "保存に成功しました。",  //个人资料保存成功

            'mail_agent_success' => "送信に成功しました。",  // agent咨询发送成功

            'user_not_exist' => "アカウントが登録されていません。",//修改邮件或密码时用户不存在
            'password_error' => "正しいパスワードをご入力ください。",//修改邮件或密码时密码不正确
            'mail_identical' => "異なるメールアドレスをご入力ください。",//修改邮件时邮件相同

        ],

        'web' => [
            'job_preview' => "このページを離れます。データを保存しましたか？",
            'job_preview_input' => "保存してからプレビューを行ってください。", //请保存后再预览
            'level_page' => 'このページを離れます。データを保存しましたか？', //还没保存，离开当前页面
            'status_invite' => "選択した項目の選考状況を「書類選考中」に変更しますか？",
            'status_invite_all' => "全項目の選考状況を「書類選考中」に変更しますか？",
            'status_invite_error' => "{id}の選考状況は変更できません。再度ご選択ください",
            'record_job_exist' => "応募済みです。管理画面を開きますか？",
            'scout_job_exist' => "本求人にスカウトされましたので、スカウト管理画面を開きますか？",

            //agent  内定企业名
            'input_company_name' => "内定会社名をご記入ください。",
            'agent_request_success' => "送信に成功しました。",

            'agent_account_code_save_success' => "保存に成功しました。",
            //agent 去完善资料
            'agent_account_info' => "エージェント会社情報を完備する。",
        ],
    ],

    //美元->人民币 汇率
    'currency_rate' => 7.081,
];
