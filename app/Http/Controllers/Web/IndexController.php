<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Users;
use App\Models\User;
use App\Models\Favorite;
use App\Models\JobRecord;
use App\Models\FindPassword;
use App\Models\Company;
use App\Models\UserFound;
use Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Popular;

use App\Models\Content;

class IndexController extends BaseController
{
    public function __construct(Job $model, User $user, Favorite $favorite, JobRecord $jobRecord, UserFound $userFound, Content $content) {
        parent::__construct();
        $this->model = $model;
        $this->userModel = $user;
        $this->favorite = $favorite;
        $this->jobRecord = $jobRecord;
        $this->userFound = $userFound;
        $this->content = $content;
    }

    function find(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->only(['email']);

            $error = [
                'status' => '200',
                'msg' => str_replace('{mail}', $data['email'], config('code.alert_msg.account.mail_send_success')),
            ];

            $validator = Validator::make($data, [
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

            $result = $this->userModel->getItemByEmail($data['email']);

            if (!$result) {
                $error['status'] = '410';
                $error['msg'] = config('code.alert_msg.account.mail_not_exist');
                return response()->json($error);
            }

            $md5Str = md5($data['email'] . time());
            $findPassword = new FindPassword();
            $findInfo = $findPassword->where('type', 1)->where('email', $data['email'])->where("deleted", 0)->first();
            if (!$findInfo) {
                $insert = [
                    'user_id' => $result->id,
                    'email' => $data['email'],
                    'hash' => $md5Str,
                    'type' => 1,
                ];

                $rs = $findPassword->saveItem($insert);
            } else {
                $insert = [
                    'id' => $findInfo->id,
                    'hash' => $md5Str,
                ];

                $rs = $findPassword->saveItem($insert);
            }

            if ($rs === false) {
                $error['status'] = '410';
                $error['msg'] = config('code.alert_msg.system.error');
                return response()->json($error);
            }

            //mail
            $url = route("web.index.findPassword") . "?hash={$md5Str}";
            $mailUser = [
                'url' => $url,
                'name' => $result['nickname'],
            ];
            $to = $data['email'];
            $subject = "「www.findjapanjob.com」のパスワードのリセットリンクをご案内します";
            Mail::send(
                'emails.find',
                ['content' => $mailUser],
                function ($mailUser) use($to, $subject) {
                    $mailUser->to($to)->subject($subject);
                }
            );

            $error['url'] = route("web.index.findOk") . "?email={$data['email']}";

            return response()->json($error);
        }

        return view('web.' . $this->viewName . '.find');
    }

    function findOk(Request $request) {
        $email = $request->input("email");
        return view('web.index.findOk', ['email' => $email]);
    }

    function findPassword(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->only(['email', 'password_new', 'hash']);
            $error = [
                'status' => '200',
                'msg' => config('code.alert_msg.account.password_success'),
            ];

            $validator = Validator::make($data, [
                //'email'             => 'required|email',
                'password_new'          => 'required|regex:/^\w{6,12}$/i',
                'hash'              => 'required',
            ],[
                'password_new.required' => config('code.alert_msg.account.password_required'),
                'password_new.regex' => config('code.alert_msg.account.password_regex'),
                'hash.required' => config('code.alert_msg.system.error'),
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => $validator->errors()->all(),
                ]);
            }

            $findPassword = new FindPassword();
            $findInfo = $findPassword->where('type', 1)->where("hash", $data['hash'])->where("deleted", 0)->first();

            if (!$findInfo) {
                $error['status'] = '410';
                $error['msg'] = config('code.alert_msg.system.error');
                return response()->json($error);
            }

            $update = [
                'id' => $findInfo->user_id,
                'password' => Hash::make($data['password_new']),
            ];

            $rs = $this->userModel->saveItem($update);
            if ($rs === false) {
                $error['status'] = '410';
                $error['msg'] = config('code.alert_msg.system.error');
                return response()->json($error);
            }

            $update = [
                'id' => $findInfo->id,
                'deleted' => 1,
            ];
            $findPassword->saveItem($update);

            return response()->json($error);
        } else {
            $hash = $request->input("hash");
            return view('web.' . $this->viewName . '.findPassword', ['hash' => $hash]);
        }
    }


    public function parseSearch($data) {
        $keywordArr = [];
        $sh = [];
        if ($data['search_text']) {
            $wage_type = array_flip(config('code.resume.wage_type'));
            $search_text = explode('、', str_replace(['　',' ',",","，"], '、', $data['search_text']));
            foreach($search_text as $v) {
                $v = str_replace(['上限なし'], '9999以下', $v);
                if (strstr($v, "N4以下")) {
                    $keywordArr[] = $v;
                } elseif (strstr($v, "〜")) {
                    $preg = "/^(年俸|月給|時給*)([\d]*).*?〜([\d]*).*?$/u";
                    preg_match($preg, $v, $arr);
                    if ($arr[1] && is_numeric($arr[2]) && is_numeric($arr[3])) {
                        $sh['wage_type'] = $wage_type[$arr[1]];
                        $sh['from'] = $arr[2];
                        $sh['to'] = $arr[3];
                    }
                } elseif (strstr($v, "以下")) {
                    $preg = "/^(年俸|月給|時給*)([\d]*).*?以下$/u";
                    preg_match($preg, $v, $arr);
                    if ($arr[1] && is_numeric($arr[2])) {
                        $sh['wage_type'] = $wage_type[$arr[1]];
                        $sh['from'] = 1;
                        $sh['to'] = $arr[2];
                    }
                } elseif (strstr($v, "以上")) {
                    $preg = "/^(年俸|月給|時給*)([\d]*).*?以上$/u";
                    preg_match($preg, $v, $arr);
                    if ($arr[1] && is_numeric($arr[2])) {
                        $sh['wage_type'] = $wage_type[$arr[1]];
                        $sh['from'] = $arr[2];
                        $sh['to'] = 9999;
                    }
                } else {
                    $keywordArr[] = $v;
                }
            }
        }

        $jp_level_str = config("code.resume.jp_level_str");
        $jpArr = [];
        foreach ($keywordArr as $k => $v) {
            if (isset($jp_level_str[$v])) {
                $jpArr = $jp_level_str[$v];
                $jpArr[] = $v;
            } elseif ($v == "N1" || $v == "N2") {
                $jpArr[] = "日本語ビジネスレベル";
                $jpArr[] = $v;
            } elseif ($v == "N3") {
                $jpArr[] = $v;
                $jpArr[] = "日本語日常会話レベル";
            }
        }

        $sh['word_jp'] = array_unique($jpArr);
        $keywordArr = array_merge($keywordArr, $jpArr);
        $sh['word'] = array_unique($keywordArr);
        return $sh;
    }

    public function index(Request $request)
    {
        $data = $request->input("sh");
        $baseInfo= $this->user;
        $founds = $this->userFound->getAllFound();
        return view('web.' . $this->viewName . '.index', ['baseInfo' => $baseInfo, "founds"=> $founds]);
    }

    function login(Request $request) {
        if (Auth::guard('web')->user()) {
            return redirect("/");
        }
        if ($request->isMethod('post')) {
            $data = $request->only(['email', 'password', 'remember']);
            $validator = Validator::make($data, [
                'email'             => 'required',
                'password'          => 'required|regex:/^\w{6,12}$/i',
            ],[
                'email.required' => config('code.alert_msg.account.mail_required'),
                'password.required' => config('code.alert_msg.account.password_required'),
                'password.regex' => config('code.alert_msg.account.login_error'),
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 404,
                    'msg' => $validator->errors()->first(),
                ]);
            }
            if (!Auth::guard('web')->attempt(['email' => $data['email'], 'deleted' => 0, 'password' => $data['password']], $request->filled('remember'))) {
                return response()->json([
                    'status' => 410,
                    'msg' => config('code.alert_msg.account.login_error'),
                ]);
            }
            return response()->json([
                'status' => 200,
                'msg' => config('code.alert_msg.account.login_success'),
                'url' => session()->get('login_url'),
            ]);
        } else {
            $url = url()->previous();
            session()->put('login_url', $url);
        }

        return view('web.' . $this->viewName . '.login');
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('web')->user();
        Auth::guard('web')->logout();
        //$request->session()->invalidate();
        //清除用户记录
        if (!!$user) {
            $userModel = new Users();
            $userModel->where('id', $user['id'])->update(['remember_token' => '']);
        }
        //$request->session()->flush();
        return redirect()->route('web.index.login');
    }

    function layout() {
        return redirect()->route('web.index.index');
    }

    //基金项目一览
    function project(Request $request, $id)
    {
        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_total = 0;//基金投资总额
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            if ($found['currency']==1) {
                $v->self_amount = round($v->amount_cn*$found['proportion']*0.01, 2);
            } else {
                $v->self_amount = round($v->amount_us*$found['proportion']*0.01, 2);
            }
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $v->listed_val = round($v->listed_val * $v->rate * 0.01, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $v->listed_val = 0;
            } else {
                $v->cur_val = $v->self_amount;
                $v->listed_val = $v->amount_cn;
            }
            $found_total+= $v->self_amount;
            $total_val+=$v->cur_val;
            $v->tz_rate_show = $v->tz_rate*100;
            $v->cur_val = round($v->cur_val, 2);
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
            if ($v->amount_us=='0'||$v->amount_us=='0.00') {
                $v->amount_us = '未知';
            }
            if ($v->self_amount=='0'||$v->self_amount=='0.00') {
                $v->self_amount = '未知';
            }
        }

        return view('web.' . $this->viewName . '.project', ["found"=> $found, 'projects' => $projects, 'total_val' => $total_val, 'found_total' => $found_total]);
    }

    //项目详情
    function projectDetail(Request $request, $id, $found_no)
    {
        $project = $this->userFound->getProjectDetail($id);
        return view('web.' . $this->viewName . '.projectDetail', ['project' => $project, 'found_no' => $found_no]);
    }

    //项目风险
    function projectRisk(Request $request, $id, $found_no)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(8, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }

        $project = $this->userFound->getProjectDetail($id);
        $rs = $this->userFound->getfinancial($id, 5);
        return view('web.' . $this->viewName . '.projectRisk', ['project' => $project, 'found_no' => $found_no, 'rs' => $rs, 'ifper' => $ifper]);
    }

    function getProjectVal($id)
    {
        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_val = 0;
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            $found_rate = $found['proportion']/100;
            $cur_found_val = 0;
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $cur_found_val = $v->listed_val * $found_rate;
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $cur_found_val = 0;
            } else {
                $v->cur_val = $v->amount_cn * $found['proportion'] * 0.01;
                $cur_found_val = $v->amount_cn;
            }
            $total_val += $v->cur_val;
            $found_val += $cur_found_val;
        }
        return ['total_val' => $total_val,'found_val' => $found_val];
    }

    public function found(Request $request)
    {
        $data = $request->input("sh");
        $baseInfo= $this->user;
        $founds = $this->userFound->getUserFound($this->user->user_id);
        $statis = ['add_pay_total' => 0, 'subcribe_pay_total' => 0, 'pay_interest_total' => 0, 'interest_allowance_total' => 0, 'pay_amount_total' => 0, 'self_total_val' => 0];
        $rate = config('code.currency_rate');
        foreach ($founds as $k => &$v) {
            $statis['subcribe_pay_total'] += $v['subcribe_pay'];
            $statis['pay_interest_total'] += $v['pay_interest'];
            $statis['interest_allowance_total'] += $v['interest_allowance'];
            $statis['add_pay_total'] += $v['add_pay'];
            $statis['pay_amount_total'] += $v['pay_amount'];
            $cur_val = $this->getProjectVal($v['found_no']);
            $v['found_val'] = round($cur_val['found_val'], 2);
            $statis['self_total_val'] += $cur_val['total_val'];
        }
        $statis['self_total_val'] = round($statis['self_total_val'], 2);
        return view('web.' . $this->viewName . '.found', ["statis"=> $statis, 'baseInfo' => $baseInfo, "founds"=> $founds]);
    }

    //全部基金列表
    public function foundDetail(Request $request)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(3, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }
        $founds = $this->userFound->getAllFound();
        return view('web.' . $this->viewName . '.foundDetail', ['founds' => $founds, 'ifper' => $ifper]);
    }

    //单支基金详情
    public function foundDetailAbout(Request $request, $id)
    {
        $found = $this->userFound->getFoundDetail($id);
        $projects = $this->userFound->getProject($id);
        $total_val = 0;
        foreach ($projects as $k => $v) {
            $v->tz_rate = ($v->rate)/100;
            if ($v->state_val==1) {
                $v->cur_val = round($v->listed_val*$v->tz_rate, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
            } else {
                $v->cur_val = $v->amount_cn;
                $v->listed_val = $v->amount_cn;
            }
            $total_val+=$v->cur_val;
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
        }
        return view('web.' . $this->viewName . '.foundDetailAbout', ['found' => $found, 'projects' => $projects, 'total_val' => $total_val]);
    }

    //基金财务报表
    public function financial(Request $request, $id)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(4, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }
        $rs = $this->userFound->getfinancial($id, 1);
        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_total = 0;//基金投资总额
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            if ($found['currency']==1) {
                $v->self_amount = round($v->amount_cn*$found['proportion']*0.01, 2);
            } else {
                $v->self_amount = round($v->amount_us*$found['proportion']*0.01, 2);
            }
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $v->listed_val = round($v->listed_val * $v->rate * 0.01, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $v->listed_val = 0;
            } else {
                $v->cur_val = $v->self_amount;
                $v->listed_val = $v->amount_cn;
            }
            $found_total+= $v->self_amount;
            $total_val+=$v->cur_val;
            $v->tz_rate_show = $v->tz_rate*100;
            $v->cur_val = round($v->cur_val, 2);
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
            if ($v->amount_us=='0'||$v->amount_us=='0.00') {
                $v->amount_us = '未知';
            }
            if ($v->self_amount=='0'||$v->self_amount=='0.00') {
                $v->self_amount = '未知';
            }
        }
        return view('web.' . $this->viewName . '.financial', ["found"=> $found, 'projects' => $projects, 'total_val' => $total_val, 'ifper'=>$ifper, 'rs' => $rs, 'found_total' => $found_total]);
    }

    //基金审计报告
    public function audit(Request $request, $id)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(5, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }
        $rs = $this->userFound->getfinancial($id, 2);
        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_total = 0;//基金投资总额
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            if ($found['currency']==1) {
                $v->self_amount = round($v->amount_cn*$found['proportion']*0.01, 2);
            } else {
                $v->self_amount = round($v->amount_us*$found['proportion']*0.01, 2);
            }
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $v->listed_val = round($v->listed_val * $v->rate * 0.01, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $v->listed_val = 0;
            } else {
                $v->cur_val = $v->self_amount;
                $v->listed_val = $v->amount_cn;
            }
            $found_total+= $v->self_amount;
            $total_val+=$v->cur_val;
            $v->tz_rate_show = $v->tz_rate*100;
            $v->cur_val = round($v->cur_val, 2);
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
            if ($v->amount_us=='0'||$v->amount_us=='0.00') {
                $v->amount_us = '未知';
            }
            if ($v->self_amount=='0'||$v->self_amount=='0.00') {
                $v->self_amount = '未知';
            }
        }
        return view('web.' . $this->viewName . '.audit', ["found"=> $found, 'projects' => $projects, 'total_val' => $total_val, 'ifper'=>$ifper, 'rs' => $rs, 'found_total' => $found_total]);
    }

    //出资明细
    public function investment(Request $request, $id)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(6, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }
        $capitals = $this->userFound->getCapital($id);
        $single = [];
        $group = [];
        $total_single = 0;
        $total_single_us = 0;
        $total_group = 0;
        $total_group_us = 0;
        foreach ($capitals as $ck => $cv) {
            $cv->date = date('Y年m月d日', strtotime($cv->paid_date));
            if ($cv->user_id == $this->user->user_id) {
                $single[] = $cv;
                $total_single+=$cv->amount_cn;
                $total_single_us+=$cv->amount_us;
            } else {
                $group[] = $cv;
                $total_group+=$cv->amount_cn;
                $total_group_us+=$cv->amount_us;
            }
        }

        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_total = 0;//基金投资总额
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            if ($found['currency']==1) {
                $v->self_amount = round($v->amount_cn*$found['proportion']*0.01, 2);
            } else {
                $v->self_amount = round($v->amount_us*$found['proportion']*0.01, 2);
            }
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $v->listed_val = round($v->listed_val * $v->rate * 0.01, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $v->listed_val = 0;
            } else {
                $v->cur_val = $v->self_amount;
                $v->listed_val = $v->amount_cn;
            }
            $found_total+= $v->self_amount;
            $total_val+=$v->cur_val;
            $v->tz_rate_show = $v->tz_rate*100;
            $v->cur_val = round($v->cur_val, 2);
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
            if ($v->amount_us=='0'||$v->amount_us=='0.00') {
                $v->amount_us = '未知';
            }
            if ($v->self_amount=='0'||$v->self_amount=='0.00') {
                $v->self_amount = '未知';
            }
        }
        return view('web.' . $this->viewName . '.investment', ["found"=> $found, 'projects' => $projects, 'total_val' => $total_val, 'ifper' => $ifper, 'single'=>$single, 'total_single'=>$total_single, 'total_single_us' => $total_single_us, 'group'=>$group, 'total_group'=>$total_group, 'total_group_us' => $total_group_us, 'found_total' => $found_total]);
    }

    //风险提示
    public function risk(Request $request, $id)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(1, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }
        $rs = $this->userFound->getfinancial($id, 3);
        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_total = 0;//基金投资总额
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            if ($found['currency']==1) {
                $v->self_amount = round($v->amount_cn*$found['proportion']*0.01, 2);
            } else {
                $v->self_amount = round($v->amount_us*$found['proportion']*0.01, 2);
            }
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $v->listed_val = round($v->listed_val * $v->rate * 0.01, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $v->listed_val = 0;
            } else {
                $v->cur_val = $v->self_amount;
                $v->listed_val = $v->amount_cn;
            }
            $found_total+= $v->self_amount;
            $total_val+=$v->cur_val;
            $v->tz_rate_show = $v->tz_rate*100;
            $v->cur_val = round($v->cur_val, 2);
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
            if ($v->amount_us=='0'||$v->amount_us=='0.00') {
                $v->amount_us = '未知';
            }
            if ($v->self_amount=='0'||$v->self_amount=='0.00') {
                $v->self_amount = '未知';
            }
        }
        return view('web.' . $this->viewName . '.risk', ["found"=> $found, 'projects' => $projects, 'total_val' => $total_val, 'ifper' => $ifper, 'rs' => $rs, 'found_total' => $found_total]);
    }

    //基金关系图
    public function diagram(Request $request, $id)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(2, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }
        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_total = 0;//基金投资总额
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            if ($found['currency']==1) {
                $v->self_amount = round($v->amount_cn*$found['proportion']*0.01, 2);
            } else {
                $v->self_amount = round($v->amount_us*$found['proportion']*0.01, 2);
            }
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $v->listed_val = round($v->listed_val * $v->rate * 0.01, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $v->listed_val = 0;
            } else {
                $v->cur_val = $v->self_amount;
                $v->listed_val = $v->amount_cn;
            }
            $found_total+= $v->self_amount;
            $total_val+=$v->cur_val;
            $v->tz_rate_show = $v->tz_rate*100;
            $v->cur_val = round($v->cur_val, 2);
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
            if ($v->amount_us=='0'||$v->amount_us=='0.00') {
                $v->amount_us = '未知';
            }
            if ($v->self_amount=='0'||$v->self_amount=='0.00') {
                $v->self_amount = '未知';
            }
        }
        return view('web.' . $this->viewName . '.diagram', ["found"=> $found, 'projects' => $projects, 'total_val' => $total_val, 'ifper' => $ifper, 'found_total' => $found_total]);
    }

    public function other(Request $request, $id)
    {
        $permissions = $this->user->permissions;
        $permissionArr = explode(',', $permissions);
        if (in_array(7, $permissionArr)) {
            $ifper = 1;
        } else {
            $ifper = 0;
        }
        $rs = $this->userFound->getfinancial($id, 4);
        $projects = $this->userFound->getProject($id);
        $found = $this->userFound->getFoundById($this->user->user_id, $id);
        $total_val = 0;
        $found_total = 0;//基金投资总额
        foreach ($projects as $k => &$v) {
            $v->tz_rate = ($v->rate * $found['proportion'])/10000;
            if ($found['currency']==1) {
                $v->self_amount = round($v->amount_cn*$found['proportion']*0.01, 2);
            } else {
                $v->self_amount = round($v->amount_us*$found['proportion']*0.01, 2);
            }
            if ($v->state_val==1) {
                $v->cur_val = $v->listed_val * $v->tz_rate;
                $v->listed_val = round($v->listed_val * $v->rate * 0.01, 2);
            } else if ($v->state_val == 2 || $v->state_val == 3) {
                $v->cur_val = 0;
                $v->listed_val = 0;
            } else {
                $v->cur_val = $v->self_amount;
                $v->listed_val = $v->amount_cn;
            }
            $found_total+= $v->self_amount;
            $total_val+=$v->cur_val;
            $v->tz_rate_show = $v->tz_rate*100;
            $v->cur_val = round($v->cur_val, 2);
            if ($v->amount_cn=='0'||$v->amount_cn=='0.00') {
                $v->amount_cn = '未知';
            }
            if ($v->amount_us=='0'||$v->amount_us=='0.00') {
                $v->amount_us = '未知';
            }
            if ($v->self_amount=='0'||$v->self_amount=='0.00') {
                $v->self_amount = '未知';
            }
        }
        return view('web.' . $this->viewName . '.other', ["found"=> $found, 'projects' => $projects, 'total_val' => $total_val, 'ifper' => $ifper, 'rs' => $rs, 'found_total' => $found_total]);
    }

    //查看文件详情
    public function filedetail(Request $request, $id, $type)
    {
        $info = $this->userFound->getFileDetail($id, $type);
        $host = $_SERVER['HTTP_HOST'];
        if (strpos($host, 'http')===false) {
            $host = 'http://'.$host;
        }
        $typeArr = explode('.', $info->path);
        $filetype = $typeArr[count($typeArr)-1];
        $url = $host . $info->path;
        if ($type==6) {
            $info->content_type = 1;
        }
        return view('web.' . $this->viewName . '.filedetail', ["info"=> $info, 'url' => $url, 'filetype' => $filetype]);
    }

    function content(Request $request, $type)
    {
        $buttom_type = config('code.buttom_type');
        $type_val = $buttom_type[$type];
        $sh = $request->input("sh");
        $rs = $this->content->getContentList($type, $sh);
        return view('web.' . $this->viewName . '.content', ['rs' => $rs, 'type' => $type, 'sh' => $sh, 'type_val'=>$type_val]);
    }

    function contentDetail(Request $request, $id)
    {
        $info = $this->content->getFoundById($id);
        return view('web.' . $this->viewName . '.contentDetail', ['info' => $info]);
    }

    function tool()
    {
        return view('web.' . $this->viewName . '.tool');
    }
}