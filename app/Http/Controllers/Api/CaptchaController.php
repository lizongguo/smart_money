<?php
/**
 * upload Controller
 *
 * @package       Api.Controller
 * @author        yutlong
 * @since         PHP 7.2
 * @version       1.0.0
 * @copyright     Copyright(C) kbf Inc.
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Captcha;
use Validator;

class CaptchaController extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        
    }
    /**
     * 验证验证码是否正确
     * @param Request $request
     */
    public function checkBankNo(Request $request) {
        $rules = ['captcha' => 'required|captcha'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            $this->back = [
                'status' => '400',
                'msg' => '验证码输入错误',
            ];
            return $this->dataToJson($this->back);
        }
        return $this->back;
    }
    
}
