<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Agent;

use Illuminate\Http\Request;
use App\Models\Resume;
use App\Models\Agent;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Models\Experience;
use App\Models\User;
use App\Models\JobRecord;

class ResumeController extends BaseController
{
    public function __construct(Resume $model, User $users) {
        parent::__construct();
        $this->model = $model;
        $this->users = $users;
    }

    function index(Request $request) {
        $data = $request->input("sh");
        $data['agent_id'] = $this->user->id;
        $sh = $this->parseSearch($data);

        $list = $this->model->getList($sh, false, $this->pageCount, "*");

        $list = $this->model->array_to_str($list);
        return view('agent.' . $this->viewName . '.index', ['list' => $list, 'agentInfo' => $this->user]);
    }

    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['job_record.status_user'])) {
            $sh['job_record.status_user'] = ['conn' => 'notin', 'value' => $data['job_record.status_user']];
        }
        return $sh;
    }

    function validatorItem(Request $request) {
        $data = $request->all();

        if (!$data['email'] || !$data['birthday'] || !$data['sex'] || !$data['nationality_id']|| !$data['name']) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }
        $email = $data['email'];

        $info = $this->users->getItemByEmail($email);

        if ($info) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.account.mail_exist'),
            ];
            return response()->json($rs);
        }

        $infoName = $this->model->where('birthday', $data['birthday'])->where('sex', $data['sex'])->where('nationality_id', $data['nationality_id'])->where('name', $data['name'])->where('user_id', '!=', 0);

        if ($data['nationality_id'] == 17) {
            $infoName = $infoName->where('nationality', $data['nationality']);
        }

        $infoName = $infoName->first();

        if ($infoName) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.account.agent_resume_exist'),
            ];
            return response()->json($rs);
        }

        $rs = [
            'status' => 200,
            'msg' => 'ok',
        ];
        return response()->json($rs);
    }

    function saveStatus(Request $request) {
        $data = $request->all();
        if (!$data['user_id'] || (!$data['agent_status'] && !$data['agent_account_code'])) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }

        $userInfo = $this->users->find($data['user_id']);
        if ($userInfo['agent_id'] != $this->user->id) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }

        $update = [
            'id' => $data['user_id'],

        ];
        if ($data['agent_status']) {
            $update['agent_status'] = $data['agent_status'];
        }

        if ($data['agent_account_code']) {
            $update['agent_account_code'] = $data['agent_account_code'];
        }


        $info = $this->users->saveItem($update);

        $rs = [
            'status' => 200,
            'msg' => config('code.alert_msg.web.agent_account_code_save_success'),
        ];

        return response()->json($rs);
    }

    function jobList(Request $request) {
        $data = $request->all();
        if (!$data['user_id']) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }

        $userInfo = $this->users->find($data['user_id']);
        if ($userInfo['agent_id'] != $this->user->id) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }

        $jobRecord = new JobRecord();
        $sh = [
            'job_record.user_id' => $data['user_id'],
            'job_record.status_user' => [1, 2, 4],
        ];
        $sh = $this->parseSearch($sh);

        $list = $jobRecord->getList($sh, true, $this->pageCount, ['job_record.status_company', 'job_record.status_user', 'job_record.status_company_time', 'job_record.read_company', 'job_record.read_company_time', 'job_record.id','job_record.created_at as job_record_created_at', "jobs.*"]);

        $rs = [
            'status' => 200,
            'msg' => 'ok',
            'list' => $list,
        ];

        return response()->json($rs);
    }

    public function input(Request $request, $id = 0)
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
                    'agent_id' => $this->user->id,
                    'agent_time' => date("Y-m-d H:i:s"),
                    'agent_over_time' => date("Y-m-d H:i:s", strtotime('+1 year')),
                    'agent_status' => 1,
                    'agent_account_code' => $data['agent_account_code'],
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
                    'recommendation' => $data['recommendation'],
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
                    'recommendation' => $data['recommendation'],
                    'toeic' => $data['toeic'] ? $data['toeic'] : '',
                    'agent_id' => $this->user->id,
                    'agent_name' => $this->user->agent_name,
                    'agent_type' => 1,
                    'principal_name' => $this->user->principal_name,
                    'agent_account_code' => $data['agent_account_code'],
                    'userCodeId' => $userCodeId,
                ];

                //admin
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


                //agent
                unset($message['agent_id']);
                $message['agent_role'] = 1;
                $to = $this->user->email;
                $subject = "{$userCodeId}_Findjapanjobから履歴データを受けました";

                Mail::send(
                    'emails.resume',
                    ['content' => $message],
                    function ($message) use($to, $subject) {
                        $message->to($to)->subject($subject);
                    }
                );

                //user
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
        return view('agent.' . $this->viewName . '.input', ['id' => $rs, 'agentInfo' => $this->user]);
    }

    function sendMail(Request $request) {
        $data = $request->all();
        if (!$data['user_id'] || !$data['content']) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }

        $userInfo = $this->users->find($data['user_id']);
        if ($userInfo['agent_id'] != $this->user->id) {
            $rs = [
                'status' => 404,
                'msg' => config('code.alert_msg.system.error'),
            ];

            return response()->json($rs);
        }

        $message = [
            'content' => $data['content'],
        ];

        $to = config("code.mail.agent_request.to");
        $cc = config("code.mail.agent_request.cc");
        $subject = "{$data['agent_name']}より委託手数料の請求";

        Mail::send(
            'emails.agent_request',
            ['content' => $message],
            function ($message) use($to, $cc, $subject) {
                $message->to($to)->cc($cc)->subject($subject);
            }
        );

        $update = [
            'id' => $data['user_id'],
            'agent_status' => 4,
            'agent_request' => 1,
            'agent_company_name' => $data['agent_company_name'],
            'agent_request_time' => date("Y-m-d H:i:s"),
        ];

        $info = $this->users->saveItem($update);

        $rs = [
            'status' => 200,
            'msg' => config('code.alert_msg.web.agent_request_success'),
        ];

        return response()->json($rs);
    }

    public function user_info(Request $request, $uid = 0)
    {
        if (!$uid) {
            exit;
        }

        $userInfo = $this->users->find($uid);
        if ($userInfo['agent_id'] != $this->user->id) {
            exit;
        }

        $sh['user_id'] = $uid;
        $recordInfo = $this->model->getList($sh, false, $this->pageCount, "*");

        $recordInfo = $this->model->array_to_str($recordInfo);
        $recordInfo = $recordInfo[0];

        if (!$recordInfo) {
            exit;
        }

        $experienceObj = new Experience();
        $uInfo = $experienceObj->where('user_id', $uid)->first();

        $recordInfo['recommendation'] = $uInfo['recommendation'];

        //处理数组字段
        $resumeInfo = config("code.resume");

        $it_skill_str = "";
        if ($recordInfo['it_skill']) {
            $it_skill = explode("," , $recordInfo['it_skill']);
            foreach ($it_skill as $v) {
                if ($v == 99) {
                    $it_skill_str .= $recordInfo['it_skill_other'] . ",";
                } else {
                    $it_skill_str .= $resumeInfo['it_skill'][$v] . ",";
                }

            }
            $it_skill_str = mb_substr($it_skill_str, 0, mb_strlen($it_skill_str)-1);
        }

        $desired_fileds_str = "";
        if ($recordInfo['desired_fileds']) {
            $desired_fileds = explode("," , $recordInfo['desired_fileds']);
            foreach ($desired_fileds as $v) {
                if ($v == 99) {
                    $desired_fileds_str .= $recordInfo['desired_fileds_other'] . ",";
                } else {
                    $desired_fileds_str .= $resumeInfo['desired_fileds'][$v] . ",";
                }

            }
            $desired_fileds_str = mb_substr($desired_fileds_str, 0, mb_strlen($desired_fileds_str)-1);
        }

        $desired_job_type_str = "";
        if ($recordInfo['desired_job_type']) {
            $desired_job_type = explode("," , $recordInfo['desired_job_type']);
            foreach ($desired_job_type as $v) {
                if ($v == 99) {
                    $desired_job_type_str .= $recordInfo['desired_job_type_other'] . ",";
                } else {
                    $desired_job_type_str .= $resumeInfo['desired_job_type'][$v] . ",";
                }

            }
            $desired_job_type_str = mb_substr($desired_job_type_str, 0, mb_strlen($desired_job_type_str)-1);
        }

        $recordInfo['desired_job_type_str'] = $desired_job_type_str;
        $recordInfo['desired_fileds_str'] = $desired_fileds_str;
        $recordInfo['it_skill_str'] = $it_skill_str;

        $rs = [
            'recordInfo' => $recordInfo,
        ];
        return view('agent.' . $this->viewName . '.user_info', $rs);
    }

}