<?php
/**
 * upload Controller
 *
 * @package       Api.Controller
 * @author        lee
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) bravesoft Inc.
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Services\Jpush\Jpush;
class PushController extends BaseController
{
    protected $jpush = null;
    protected $attachment = null;

    public function __construct(Request $request, Jpush $jpush)
    {
        parent::__construct($request);
        
        $this->jpush = $jpush;
    }
    
    /**
     *  test push
     * @param Request $request
     * @param type $type
     * @param type $aid
     * @return type
     */
    public function test(Request $request, $id)
    {
        $content = "这是一条测试push通知";
        $type = $request->input('type', '');
        $rs = $this->jpush->pushMemberMessage($id, $content, empty($type) ? 'alert' : 'message');
        
        $this->back['data'] = [
            'id' => $id,
            'type' => $type,
            'result' => $rs
        ];
        return $this->back;
    }
    
    
}
