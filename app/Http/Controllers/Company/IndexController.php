<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Company;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\FindPassword;
use Validator;
use Illuminate\Support\Facades\Mail;

class IndexController extends BaseController
{
    public function __construct(Company $model) {
        parent::__construct();
        $this->model = $model;
    }

    public function index(){
        return redirect()->route('company.job.index');
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

            $result = $this->model->getItemByEmail($data['email']);

            if (!$result) {
                $error['status'] = '410';
                $error['msg'] = config('code.alert_msg.account.mail_not_exist');
                return response()->json($error);
            }

            $md5Str = md5($data['email'] . time());
            $findPassword = new FindPassword();
            $findInfo = $findPassword->where('type', 2)->where('email', $data['email'])->where("deleted", 0)->first();
            if (!$findInfo) {
                $insert = [
                    'user_id' => $result->id,
                    'email' => $data['email'],
                    'hash' => $md5Str,
                    'type' => 2,
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
            $url = route("company.index.findPassword") . "?hash={$md5Str}";
            $mailUser = [
                'url' => $url,
                'name' => $result['company_name'],
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

            $error['url'] = route("company.index.findOk") . "?email={$data['email']}";

            return response()->json($error);
        }

        return view('company.' . $this->viewName . '.find');
    }

    function findOk(Request $request) {
        $email = $request->input("email");
        return view('company.index.findOk', ['email' => $email]);
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
                'password_new'      => 'required',
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
            $findInfo = $findPassword->where('type', 2)->where("hash", $data['hash'])->where("deleted", 0)->first();

            if (!$findInfo) {
                $error['status'] = '410';
                $error['msg'] = config('code.alert_msg.system.error');
                return response()->json($error);
            }

            $update = [
                'id' => $findInfo->user_id,
                'password' => $data['password_new'],
            ];

            $rs = $this->model->saveItem($update);
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
            return view('company.' . $this->viewName . '.findPassword', ['hash' => $hash]);
        }
    }

    function login(Request $request) {
        if (Auth::guard('company')->user()) {
            return redirect(route("company.index.index"));
        }
        if ($request->isMethod('post')) {
            $data = $request->only(['email', 'password', 'remember']);
            $validator = Validator::make($data, [
                'email'             => 'required|email',
                'password'          => 'required|regex:/^\w{6,12}$/i',
            ],[
                'email.required' => config('code.alert_msg.account.mail_required'),
                'password.required' => config('code.alert_msg.account.password_required'),
                'email.email' => config('code.alert_msg.account.mail_regex'),
                'password.regex' => config('code.alert_msg.account.password_regex'),
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 404,
                    'msg' => $validator->errors()->first(),
                ]);
            }
            if (!Auth::guard('company')->attempt(['email' => $data['email'], 'deleted' => 0, 'password' => $data['password']], $request->filled('remember'))) {
                return response()->json([
                    'status' => 410,
                    'msg' => config('code.alert_msg.account.login_error'),
                ]);
            }
            return response()->json([
                'status' => 200,
                'msg' => config('code.alert_msg.account.login_success'),
            ]);
        }

        return view('company.' . $this->viewName . '.login');
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('company')->user();
        Auth::guard('company')->logout();
        //$request->session()->invalidate();
        //清除用户记录
        if (!!$user) {
            $this->model->where('id', $user['id'])->update(['remember_token' => '']);
        }
        //$request->session()->flush();
        return redirect()->route('company.index.login');
    }

}