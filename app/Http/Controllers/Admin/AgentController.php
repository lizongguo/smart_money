<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Agent;

class AgentController extends BaseController
{
    public function __construct(Request $request, Agent $model) {
        parent::__construct();
        $this->model = $model;

        view()->share('jobInfo', config("code.company"));
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['agent_name'])) {
            $sh['agent_name'] = ['conn' => 'lk', 'value' => $data['agent_name']];
        }
        
        if (!empty($data['email'])) {
            $sh['email'] = ['conn' => '=', 'value' => $data['email']];
        }
        return $sh;
    }
    
    
    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'agent_name' => 'required',
            'type' => 'required',
            'email' => "required|email|unique:agent,email,{$data['id']},id",
        ];
        $tips = [
            'agent_name.required' => '企業名は空にできません。',
            'type.required' => 'タイプは空にできません。',
            'email.required' => 'メールは空にできません。',
            'email.unique' => 'メールはすでに存在します',
        ];
        
        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }

    function items(Request $request) {
        $rs = parent::items($request, true);
        $resumeInfo = config("code.agent.type");

        $typeStr = "";
        foreach ($rs['data'] as &$val) {
            $typeStr = "";
            foreach (explode(',', $val['type']) as $v) {
                $typeStr .= $resumeInfo[$v] . ",";
            }
            $val['type'] = mb_substr($typeStr, 0, mb_strlen($typeStr)-1);
        }

        return response()->json($rs);
    }

}
