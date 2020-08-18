<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01 
 * Time: 15:01
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Setting;
use Validator;

class SettingController extends BaseController
{
    public function __construct(Setting $model) {
        parent::__construct();
        $this->model = $model;
    }
    
    /**
     * 站点设置
     * @param Request $request
     * @return type
     */
    public function site(Request $request) {
        $data = $request->input('data', []);
        $validator = Validator::make($data, [
            'sitename' => 'required',
            'domain' => 'required|url',
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'msg' => "必填项不能为空",
            ]);
        }
        $result = file_put_contents(config_path('site.php'), "<?php\r\n return " . var_export($data, true) . ";\r\n");
        //save success
        if($result !== false) {
            return $this->dataToJson(['status' => 200, 'msg' => '站点设置保存成功']);
        }
        return $this->dataToJson(['status' => 500, 'msg' => '站点设置保存失败，请稍后再试。']);
    }
    
    /**
     * 系统设置
     * @param Request $request
     * @return type
     */
    public function system(Request $request) {
        $list = $this->model->where('shop_id', 0)->get();
        $data = [];
        foreach($list as $item) {
            $data[$item->category][$item->name] = $item['value'];
        }
        
        return view('admin.' . $this->viewName . '.system', ['data' => $data]);
    }
    
    /**
     * 系统设置存储
     * @param Request $request
     * @param type $category
     * @return type
     */
    public function save(Request $request, $category) {
        $cate_map = [
            'wxapp' => '微信小程序',
            'aliapp' => '支付宝小程序',
            'push' => '推送',
            'takeout' => '外卖配置',
            'sms' => '短信配置',
        ];
        
        if (!isset($cate_map[$category])) {
            return response()->json([
                'status' => 400,
                'msg' => '错误操作'
            ]);
        }
        
        $post = $request->input('data');
        //验证字段特殊处理检索字段
        $validatorFun = 'validator' . ucfirst($category);
        if (method_exists($this, $validatorFun) && $this->$validatorFun($post, $msg) == false) {
            return response()->json([
                'status' => 400,
                'msg' => $msg
            ]);
        }
        
        $result = $this->model->saveOptions($post, $cate_map[$category]);

        //save success
        if ($result) {
            return response()->json([
                'status' => 200,
                'msg' => '保存に成功しました。'
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'msg' => '保存に失敗しました。',
                'data' => $post
            ]);
        }
    }
    
    /**
     * 微信小程序设置验证
     * @param type $data
     * @param type $msg
     */
    protected function validatorWxapp(&$data , &$msg = null) {
        $valid = [];
        if (!isset($data['wx_app_state']) || $data['wx_app_state'] < 1) {
            $data['wx_app_state'] = 0;
            unset($data['wx_app_id'], $data['wx_app_secret'], $data['wx_mch_id'], $data['wx_mch_key']);
        }else{
            $valid['wx_app_id'] = 'regex:/^wx[\w]+/i';
            $valid['wx_app_secret'] = 'required';
            $valid['wx_mch_id'] = 'required';
            $valid['wx_mch_key'] = 'required';
        }
        
        $tips = [
            'wx_app_id.regex' => '小程序ID输入有误',
            'wx_app_secret.required' => '小程序密钥为必选项',
            'wx_mch_id.required' => '商户号ID为必选项',
            'wx_mch_key.required' => '商户号密钥为必选项',
        ];
        if (count($valid) > 0) {
            $validator = \Validator::make($data, $valid, $tips);
            if ($validator->fails()) {
                $msg = $validator->errors()->all();
                return false;
            }
        }
        return true;
    }
    
    /**
     * 阿里小程序设置验证
     * @param type $data
     * @param type $msg
     */
    protected function validatorAliapp(&$data , &$msg = null) {
        $valid = [];
        if (!isset($data['ali_app_state']) || $data['ali_app_state'] < 1) {
            $data['ali_app_state'] = 0;
            unset($data['wx_app_id'], $data['wx_app_secret'], $data['wx_mch_id'], $data['wx_mch_key']);
        }else{
            $valid['ali_app_id'] = 'regex:/^[\d]{10,20}$/';
            $valid['ali_alipayrsaPublicKey'] = 'required';
            $valid['ali_rsaPrivateKey'] = 'required';
        }
        
        $tips = [
            'ali_app_id.regex' => '小程序ID输入有误',
            'ali_alipayrsaPublicKey.required' => '支付宝公钥为必选项',
            'ali_rsaPrivateKey.required' => '小程序密钥为必选项',
        ];
        if (count($valid) > 0) {
            $validator = \Validator::make($data, $valid, $tips);
            if ($validator->fails()) {
                $msg = $validator->errors()->all();
                return false;
            }
        }
        return true;
    }
    
    /**
     * 外卖设置验证
     * @param type $data
     * @param type $msg
     */
    protected function validatorTakeout(&$data , &$msg = null) {
        $valid = [];
        if (!isset($data['other_service_state']) || $data['other_service_state'] < 1) {
            $data['other_service_state'] = 0;
            unset($data['takeout_company'], $data['takeout_appKey'], $data['takeout_appSecret']);
        }else{
            $valid['takeout_appKey'] = 'required';
            $valid['takeout_appSecret'] = 'required';
        }
        
        $tips = [
            'takeout_appSecret.required' => '配送公司AppKey为必选项',
            'takeout_appSecret.required' => '配送公司AppSecret为必选项',
        ];
        if (count($valid) > 0) {
            $validator = \Validator::make($data, $valid, $tips);
            if ($validator->fails()) {
                $msg = $validator->errors()->all();
                return false;
            }
        }
        return true;
    }
}
