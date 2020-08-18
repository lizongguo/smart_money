<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/24 0024
 * Time: 上午 11:41
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admins;
use Validator;

class LoginController extends Controller
{
    public function __construct() {
//        dd(Auth::guard('admin')->user());
        view()->share('code', config('code'));
    }
    
    /**
     * 管理平台登录功能
     */
    public function login(Request $request){
        $userModel = new Admins();
        if ($request->isMethod('post')) {
            $data = $request->only(['email', 'password', 'remember', 'captcha']);

            $validator = Validator::make($data, [
                'email'             => 'required|email',
                'password'          => 'required|regex:/^\w{6,12}$/i',
                //'captcha'          => 'required|regex:/^\w{4}$/i|captcha',
            ],[
                'email.required' => '账号不能为空',
                'password.required' => '密码不能为空',
                'email.email' => '账号格式不正确',
                'password.regex' => '账号不存在或密码错误',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => $validator->errors()->all(),
//                    'data' => 
                ]);
            }
//            dd(\Hash::make($data['password']));
            if (!Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']], $request->filled('remember'))) {
                return response()->json([
                    'status' => 410,
                    'msg' => '账号不存在或密码错误',
                ]);
            }
            $admin = $userModel->where('email', $data['email'])->first();
            
//            $remember = $request->input('remember');
            $admin->access_token = md5($userModel->getRandomStr(6) . time());
//            //设置自动登录
//            if($remember =='on'){
//                $admin->remember_token = md5($userModel->getRandomStr(6) . time());
//                $tempCookie = Cookie::make('remember', $admin->remember_token, 15 * 24 * 60);
//                Cookie::queue($tempCookie);
//            }
            $admin->save();
            
            return response()->json([
                'status' => 200,
                'msg' => '登录成功',
                'data' => ['access_token' => $admin->access_token],
            ]);
            
        }
        
        //用户已登录
        if (Auth::guard('admin')->user())
        {
            $backurl = $request->session()->get('backurl');
            if(empty($backurl) || !preg_match("#^".env('APP_URL', 'http://www.yangfugui.com')."#is", $backurl) || preg_match('#/admin/login#is', $backurl)) {
                $backurl = route('admin.index');
            }
            $request->session()->forget('backurl');
            return redirect($backurl);
        }
        return view('admin.login.login');
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();
        Auth::guard('admin')->logout();
        //$request->session()->invalidate();
        //清除用户记录
        if (!!$user) {
            $userModel = new Admins();
            $userModel->where('id', $user['id'])->update(['remember_token' => '']);
        }
        //$request->session()->flush();
        return redirect()->route('admin.login');
    }
    
}
