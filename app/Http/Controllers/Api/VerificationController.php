<?php
/**
 * upload Controller
 *
 * @package       Api.Controller
 * @author        lee
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) kbf Inc.
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Services\Aliyun\Alidayu;
use App\Services\Baidu\BaidusmsService;
use Illuminate\Support\Facades\Redis;
use Validator;

class VerificationController extends BaseController
{
    protected $sender = null;
    protected $templateCodes = [
//        'register' => 'smsTpl:e7476122a1c24e37b3b0de19d04ae901',
//        'change' => 'smsTpl:e7476122a1c24e37b3b0de19d04ae900',
        'register' => 'SMS_143575009', //小程序注册
        'forgetpwd' => 'SMS_143575008', //服务员忘记密码
    ];
    //5分钟失效
    protected $invalidMinute = 5;

    public function __construct(Request $request, Alidayu $sender)
    {
        parent::__construct($request);
        $this->sender = $sender;
        $this->invalidMinute = config('code.invalid_minute');
    }
    
    /**
     * 验证字段是否已注册
     * @param $request
     * @return type
     */
    public function send(Request $request, $type)
    {
        $phone = $request->input('phone', null);
        
        if(!isset($this->templateCodes[$type])) {
            $this->back['status'] = '400';
            $this->back['msg'] = '请求的地址不可访问。';
            return $this->dataToJson($this->back);
        }
        
        $validator = Validator::make($request->all(), [
            'phone' => 'regex:#^1[\d]{10}$#',
        ], [
            'phone.regex' => '手机号格式输入有误。'
        ]);
        
        if ($validator->fails()) {
            $this->back['status'] = '400';
            $this->back['msg'] = implode(',', $validator->errors()->all());
            return $this->dataToJson($this->back);
        }
        
        $redisKey = config("rediskeys.verification_{$type}_hash");
        
        if (Redis::hexists($redisKey, $phone)) {
            $dataStr = Redis::hget($redisKey, $phone);
            $data = json_decode($dataStr, true);
            //时间间隔太短
            $sec = time() - 60 - $data['time'];
            if($sec < 0) {
                $this->back['status'] = '400';
                $this->back['msg'] = '发送短信太快，请'.( 0 -$sec ).'秒后在试。';
                return $this->dataToJson($this->back);
            }
            
            $data['time'] = time();
            $code = $data['code'];
        } else {
            $code = str_pad(mt_rand(1, 9999), '4', 0, 0);
            $data = ['code' => $code, 'time' => time()];
        }
        
        $params = [
            'code' => $code
        ];
        
        $rs = $this->sender->sendSms($phone, $params, $this->templateCodes[$type]);
        
        if(!$rs) {
            $this->back = [
                'status' => '500',
                'msg' => '验证码发送失败，请稍后重试！'
            ];
            return $this->dataToJson($this->back);
        }
        Redis::hset($redisKey, $phone, json_encode($data));
        
        return $this->back;
    }
    
    /**
     * 检测银行卡 输入是否错误
     * @param Request $request
     */
    public function checkBankNo(Request $request) {
        $id_card = $request->input('id_card', '');
        if (empty($id_card) || preg_match('#[^\d]#', $id_card)) {
            $this->back = [
                'status' => '431',
                'msg' => '银行卡号码输入错误。'
            ];
            return $this->dataToJson($this->back);
        }
        $uri = 'https://ccdcapi.alipay.com/validateAndCacheCardInfo.json?cardNo='.$id_card.'&cardBinCheck=true';
        $content = @file_get_contents($uri);
        $data = json_decode($content, true);
        $this->back['data'] = (json_last_error() == JSON_ERROR_NONE) ? $data : [];
        
        return $this->back;
    }
    
}
