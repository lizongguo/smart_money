<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use Validator;

class CompanyController extends BaseController
{
    public function __construct(Company $model) {
        parent::__construct();
        $this->model = $model;
    }

    function index() {

    }

    function account() {
        return view('company.' . $this->viewName . '.account', ['email' => $this->user->email]);
    }

    function changePassword(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->only(['password_old', 'password_new']);

            $rs = [
                'status' => '200',
                'msg' => config('code.alert_msg.account.password_success'),
            ];

            $this->model->changePassword($this->user->id, $data['password_old'], $data['password_new'], $rs);

            return response()->json($rs);
        }

        return view('company.' . $this->viewName . '.changePassword', ['email' => $this->user->email]);
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

            $this->model->changeEmail($this->user->id, $data['password'], $data['email'], $rs);

            return response()->json($rs);
        }

        return view('company.' . $this->viewName . '.changeEmail', ['email' => $this->user->email]);
    }

    function info(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $data['id'] = $this->user->id;
            //print_r($data);exit;
            $id = $this->model->saveItem($data);

            $rs = [
                'status' => '200',
                'msg' => config('code.alert_msg.account.info_success'),
            ];
            if ($id === false) {
                $rs = [
                    'status' => '500',
                    'msg' => config('code.alert_msg.system.error'),
                ];
            }

            return response()->json($rs);
        }

        $company = $this->model->find($this->user->id);
        return view('company.' . $this->viewName . '.info', ['data' => $company]);
    }

}