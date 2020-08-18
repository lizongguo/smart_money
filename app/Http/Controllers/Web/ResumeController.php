<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Models\Experience;
use App\Models\Contact;

class ResumeController extends BaseController
{
    public function __construct(Resume $model, User $users) {
        parent::__construct();
        $this->model = $model;
        $this->users = $users;
    }

    function validatorItem(Request $request) {
        $email = $request->input("email");
        if (!$email) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }

        $validator = Validator::make(["email" => $email], [
            'email'             => 'required|email',
        ],[
            'email.required' => config('code.alert_msg.account.mail_required'),
            'email.email' => config('code.alert_msg.account.mail_regex'),
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'msg' => $validator->errors()->all(),
            ]);
        }

        $info = $this->users->getItemByEmail($email);

        if ($info) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.account.mail_exist'),
            ];
        } else {
            $rs = [
                'status' => 200,
                'msg' => 'ok',
            ];
        }

        return response()->json($rs);
    }

    function mailGlobal(Request $request) {
        $Resume = $request->input('resume');

        $nationalityArr = ["","中国","ベトナム","ミャンマー","インド","フィリピン","インドネシア","その他"];
        $addressArr = ["","日本","日本以外でも可"];
        $target_audienceArr = ["","新卒","経験者"];
        //$literary_selectionArr = ["","文系","理系"];
        $jp_levelArr = ["","N1","N2","N3","それ以下"];
        $en_levelArr = ["","ネイティブレベル","ビジネスレベル","日常会話レベル"];

        $nationalityStr = "";
        if ($Resume['nationality']) {
            foreach (explode(',', $Resume['nationality']) as $v) {
                $nationalityStr .= $nationalityArr[$v] . ",";
            }

            $nationalityStr = rtrim($nationalityStr, ',');
        }

        $addressStr = "";
        if ($Resume['address']) {
            foreach (explode(',', $Resume['address']) as $v) {
                $addressStr .= $addressArr[$v] . ",";
            }

            $addressStr = rtrim($addressStr, ',');
        }

        $target_audienceStr = "";
        if ($Resume['target_audience']) {
            foreach (explode(',', $Resume['target_audience']) as $v) {
                $target_audienceStr .= $target_audienceArr[$v] . ",";
            }

            $target_audienceStr = rtrim($target_audienceStr, ',');
        }

        $ja_levelStr = "";
        if ($Resume['jp_level']) {
            foreach (explode(',', $Resume['jp_level']) as $v) {
                $ja_levelStr .= $jp_levelArr[$v] . ",";
            }

            $ja_levelStr = rtrim($ja_levelStr, ',');
        }

        $en_levelStr = "";
        if ($Resume['en_level']) {
            foreach (explode(',', $Resume['en_level']) as $v) {
                $en_levelStr .= $en_levelArr[$v] . ",";
            }

            $en_levelStr = rtrim($en_levelStr, ',');
        }

        $arr = [
            'company' => $Resume['company'],
            'company_url' => $Resume['company_url'],
            'name' => $Resume['name'],
            'cell_phone' => $Resume['cell_phone'],
            'email' => $Resume['email'],
            'content' => $Resume['content'],
            'nationality' => $nationalityStr,
            'address' => $addressStr,
            'target_audience' => $target_audienceStr,
            'jp_level' => $ja_levelStr,
            'en_level' => $en_levelStr,
        ];

        $to = config("code.mail.global.to");
        $cc = config("code.mail.global.cc");
        $subject = "【findjapanjob】企業からお問い合わせがありました";

        Mail::send(
            'emails.global',
            ['content' => $arr],
            function ($arr) use($to, $cc, $subject) {
                $arr->to($to)->cc($cc)->subject($subject);
            }
        );

        $to = $Resume['email'];
        $subject = "「www.findjapanjob.com」にお問い合わせいただきありがとうございます";
        Mail::send(
            'emails.global_company',
            ['content' => $arr],
            function ($mailUser) use($to, $subject) {
                $mailUser->to($to)->subject($subject);
            }
        );

        return response()->json([]);
    }

    function mailAbout(Request $request) {
        $Resume = $request->input('resume');

        $planArr = ["","ライトプラン（２万円）","新卒プラン（５万円）","経験者プラン（７万円）"];
        $jp_levelArr = ["","N1","N2","N3","それ以下"];
        $addressArr = ["","日本","日本以外"];

        $addressStr = "";
        if ($Resume['address']) {
            foreach (explode(',', $Resume['address']) as $v) {
                $addressStr .= $addressArr[$v] . ",";
            }

            $addressStr = rtrim($addressStr, ',');
        }

        $jp_leveStr = "";
        if ($Resume['jp_level']) {
            foreach (explode(',', $Resume['jp_level']) as $v) {
                $jp_leveStr .= $jp_levelArr[$v] . ",";
            }

            $jp_leveStr = rtrim($jp_leveStr, ',');
        }

        $arr = [
            'name' => $Resume['name'],
            'email' => $Resume['email'],
            'cell_phone' => $Resume['cell_phone'],
            'plan' => $planArr[$Resume['plan']],
            'nationality' => $Resume['nationality'],
            'address' => $addressStr,
            'jp_level' => $jp_leveStr,
            'message' => $Resume['message'],
        ];

        $to = config("code.mail.about.to");
        $cc = config("code.mail.about.cc");
        $subject = "日本就職塾";

        Mail::send(
            'emails.about',
            ['content' => $arr],
            function ($arr) use($to, $cc, $subject) {
                $arr->to($to)->cc($cc)->subject($subject);
            }
        );

        return response()->json([]);
    }

    function mailAboutContact(Request $request) {
        $Resume = $request->input('resume');

        $contactObj = new Contact();
        $id = $contactObj->saveItem($Resume);

        $planArr = ["","ライトプラン（２万円）","新卒プラン（５万円）","経験者プラン（７万円）"];
        $jp_levelArr = ["","N1","N2","N3","それ以下"];
        $addressArr = ["","日本","日本以外"];

        $arr = [
            'name' => $Resume['name'],
            'email' => $Resume['email'],
            'cell_phone' => $Resume['cell_phone'],
            'plan' => $planArr[$Resume['plan']],
            'nationality' => $Resume['nationality'],
            'address' => $addressArr[$Resume['address']],
            'jp_level' => $jp_levelArr[$Resume['jp_level']],
            'message' => $Resume['message'],
        ];

        $to = config("code.mail.about.to");
        $cc = config("code.mail.about.cc");
        $subject = "{$id}_日本就職塾";

        Mail::send(
            'emails.about_content',
            ['content' => $arr],
            function ($arr) use($to, $cc, $subject) {
                $arr->to($to)->cc($cc)->subject($subject);
            }
        );

        return response()->json([]);
    }

    public function add(Request $request, $id = 0)
    {
        $rs = 0;
        $data = $request->all();
        if ($data) {
            if($data['employment_status'] == 1) {
                $data['employment_status_extra'] = $data['employment_status_extra_1'];
            } else {
                $data['employment_status_extra'] = $data['employment_status_extra_2'];
            }

            $data['toeic'] = $data['toeic'] ? $data['toeic'] : 0;
            $data['address_id'] = $data['address_id'] ? $data['address_id'] : 0;
            unset($data['employment_status_extra_1'], $data['employment_status_extra_2']);
//            echo "<pre>";
//            print_r($data);exit;
            $id = $this->model->saveItem($data);

            if ($id) {

                $password = substr(md5(time()), 0,6);
                $userInsert = [
                    'email' => $data['email'],
                    'nickname' => $data['name'],
                    'password' => \Hash::make($password),
                    'created_at' => date("Y-m-d H:i:s"),
                ];
                $userId = $this->users->saveItem($userInsert);

                $userCodeId = $this->getNewCodeId("user", '', $userId);

                //resume 绑定用户
                $update = [
                    'resume_id' => $id,
                    'user_id' => $userId,
                    'account_code' => $userCodeId,
                ];

                $resumeId = $this->model->saveItem($update);

                //填充履历
                $experience = [
                    'user_id' => $userId,

                    'name' => $data['name'],
                    'sex' => $data['sex'],
                    'birthday' => $data['birthday'],
                    'nationality_id' => $data['nationality_id'],
                    'nationality' => $data['nationality'],
                    'address' => $data['address'],
                    'address_id' => $data['address_id'],
                    'cell_phone' => $data['cell_phone'],
                    'email' => $data['email'],
                    //'visa_type' => $data['visa_type'],
                    //'visa_other' => $data['visa_other'],
                    //'visa_term' => $data['visa_term'],
                    'pr_other' => $data['pr_other'],
                ];

                $experienceObj = new Experience();
                $experienceId = $experienceObj->saveItem($experience);

                $rs = 1;

                $resumeInfo = config("code.resume");
                if ($data['address'] == 1) {
                    if ($data['address_extra_2'] == -1) {
                        $address_extra = "1年未満";
                    } elseif ($data['address_extra_2'] == 99) {
                        $address_extra = "10年以上";
                    } elseif ($data['address_extra_2'] == 0) {
                        $address_extra = "0年";
                    } else {
                        $address_extra = $data['address_extra_2'] . "年";
                    }

                    $visaType = $resumeInfo['visa_type'][$data['visa_type']];
                    if ($visaType == "その他" && $data['visa_other']) {
                        $visaType = $data['visa_other'];
                    }

                    $zhusuo = [
                        "現住所：日本[{$resumeInfo['country_city'][$data['address_id']]}]",
                        "日本滞在年数：{$address_extra}",
                        //"ビザ種類：{$visaType}",
                        //"ビザ有効期限：{$data['visa_term']}"
                    ];
                } else {
                    $zhusuo = ["現住所：日本以外"];
                }

                if ($data['employment_status'] == 1) {
                    $jiuzhi = [
                        "就業状況：在学中",
                        "卒業見込み：{$data['employment_status_extra']}年"
                    ];
                } else {
                    if ($data['employment_status_extra'] == -1) {
                        $address_extra = "1年未満";
                    } elseif ($data['employment_status_extra'] > 10) {
                        $address_extra = "10年以上";
                    } else {
                        $address_extra = $data['employment_status_extra'] . "年";
                    }
                    $jiuzhi = [
                        "就業状況：在職中",
                        "仕事経験年数：{$address_extra}"
                    ];
                }

                $desired_place_arr = $resumeInfo['country_city'];
                $desired_placeStr = "";
                if ($data['desired_place_ids']) {
                    $desired_place_ids_arr =  explode(',', $data['desired_place_ids']);
                    if (in_array("9999", $desired_place_ids_arr)) {
                        $desired_placeStr = "日本全国";
                    } else {
                        foreach ($desired_place_ids_arr as $v) {
                            $desired_placeStr .= $desired_place_arr[$v] . ",";
                        }

                        $desired_placeStr = rtrim($desired_placeStr, ',');
                    }
                }

                $know_way_arr = $resumeInfo['know_way'];
                if ($data['know_way'] == 7 && $data['know_way_other']) {
                    $know_way = $data['know_way_other'];
                } else {
                    $know_way = $know_way_arr[$data['know_way']];
                }

                $it_skill_arr = $resumeInfo['it_skill'];
                $it_skillStr = "";
                if ($data['it_skill']) {
                    foreach (explode(',', $data['it_skill']) as $v) {
                        if ($it_skill_arr[$v]) {
                            $it_skillStr .= $it_skill_arr[$v] . ",";
                        }
                    }
                    if ($data['it_skill_other']) {
                        $it_skillStr .= $data['it_skill_other'];
                    }

                    $it_skillStr = rtrim($it_skillStr, ',');
                }

                $desired_fileds_arr = $resumeInfo['desired_fileds'];

                $desired_filedsStr = "";
                if ($data['desired_fileds']) {
                    foreach (explode(',', $data['desired_fileds']) as $v) {
                        if ($desired_fileds_arr[$v]) {
                            $desired_filedsStr .= $desired_fileds_arr[$v] . ",";
                        }
                    }
                    if ($data['desired_fileds_other']) {
                        $desired_filedsStr .= $data['desired_fileds_other'];
                    }

                    $desired_filedsStr = rtrim($desired_filedsStr, ',');
                }

                $desired_job_type_arr = $resumeInfo['desired_job_type'];

                $desired_job_typeStr = "";
                if ($data['desired_job_type']) {
                    foreach (explode(',', $data['desired_job_type']) as $v) {
                        if ($desired_job_type_arr[$v]) {
                            $desired_job_typeStr .= $desired_job_type_arr[$v] . ",";
                        }
                    }
                    if ($data['desired_job_type_other']) {
                        $desired_job_typeStr .= $data['desired_job_type_other'];
                    }

                    $desired_job_typeStr = rtrim($desired_job_typeStr, ',');
                }

                if ($data['birthday']) {
                    $age_str = floor((time() - strtotime($data['birthday'])) / (3600 * 24 * 365)) . "歳";
                } else {
                    $age_str = "不明";
                }

                $nationality = $resumeInfo['nationality'][$data['nationality_id']];
                if ($nationality == "その他" && $data['nationality']) {
                    $nationality = $data['nationality'];
                }

                $birthday = date("Y年m月d日", strtotime($data['birthday']));

                $message = [
                    'name' => $data['name'],
                    'sex' => $resumeInfo['sex'][$data['sex']],
                    'age' => $age_str . "[{$birthday}]",
                    'nationality' => $nationality,
                    'zhusuo' => $zhusuo,
                    'jiuzhi' => $jiuzhi,
                    'final_education' => $resumeInfo['final_education'][$data['final_education']],
                    'science_arts' => $resumeInfo['science_arts'][$data['science_arts']],
                    'university' => $data['university'],
                    'major' => $data['major'],
                    'jp_level' => $resumeInfo['jp_level_2'][$data['jp_level']],
                    'interview' => $resumeInfo['interview'][$data['interview']],
                    'en_level' => $resumeInfo['en_level'][$data['en_level']],
                    'email' => $data['email'],
                    'cell_phone' => $data['cell_phone'],
                    'wechat_id' => $data['wechat_id'],
                    'line_id' => $data['line_id'],
                    'skype_id' => $data['skype_id'],
                    'know_way' => $know_way,
                    'it_skill' => $it_skillStr,
                    'desired_fileds' => $desired_filedsStr,
                    'desired_job_type' => $desired_job_typeStr,
                    'desired_place' => $desired_placeStr,
                    'pr_other' => $data['pr_other'],
                    'role' => "admin",
                    'id' => $id,
                    'address' => $data['address'],
                    'toeic' => $data['toeic'] ? $data['toeic'] : '',
                    'userCodeId' => $userCodeId,
                ];

                $to = config("code.mail.findjapanjob.to");
                $cc = config("code.mail.findjapanjob.cc");
                $subject = "{$userCodeId}_Findjapanjobから履歴データを受けました";

                Mail::send(
                    'emails.resume',
                    ['content' => $message],
                    function ($message) use($to, $cc, $subject) {
                        $message->to($to)->cc($cc)->subject($subject);
                    }
                );

                //
//                $message['role'] = "user";
//                $to = $message['email'];
//                $subject = "{$id}_履歴データをお送りいただきありがとうございます";
//                Mail::send(
//                    'emails.resume',
//                    ['content' => $message],
//                    function ($message) use($to, $subject) {
//                        $message->to($to)->subject($subject);
//                    }
//                );


                $mailUser = [
                    'email' => $data['email'],
                    'password' => $password,
                    'name' => $data['name'],
                ];

                $to = $data['email'];
                $subject = "「www.findjapanjob.com」にエントリーいただきありがとうございます";
                Mail::send(
                    'emails.register',
                    ['content' => $mailUser],
                    function ($mailUser) use($to, $subject) {
                        $mailUser->to($to)->subject($subject);
                    }
                );
            }
        }
        return view('web.' . $this->viewName . '.add', ['id' => $rs]);
    }

}