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

class UserController extends BaseController
{
    public function __construct(Resume $model, User $users) {
        parent::__construct();
        $this->model = $model;
        $this->users = $users;
    }

    function index() {

    }

    function account() {
        return view('web.' . $this->viewName . '.account', ['email' => $this->user->email]);
    }

    function changePassword(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->only(['password_old', 'password_new']);

            $rs = [
                'status' => '200',
                'msg' => config('code.alert_msg.account.password_success'),
            ];

            $this->users->changePassword($this->user->id, $data['password_old'], $data['password_new'], $rs);

            return response()->json($rs);
        }

        return view('web.' . $this->viewName . '.changePassword', ['email' => $this->user->email]);
    }

    function changeEmail(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->only(['email', 'password']);
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
            $rs = [
                'status' => '200',
                'msg' => config('code.alert_msg.account.mail_success'),
            ];

            $this->users->changeEmail($this->user->id, $data['password'], $data['email'], $rs);

            return response()->json($rs);
        }

        return view('web.' . $this->viewName . '.changeEmail', ['email' => $this->user->email]);
    }

}