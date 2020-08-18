<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends BaseController
{
    public function __construct(Request $request, Contact $model) {
        parent::__construct();
        $this->model = $model;
    }
    
    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    
    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['name'])) {
            $sh['name'] = ['conn' => 'lk', 'value' => str_replace(['\\', '%', '_'], ['\\'.'\\', '\\'.'%', '\\'.'_'], $data['name'])];
        }
        
        if (!empty($data['email'])) {
            $sh['email'] = ['conn' => '=', 'value' => $data['email']];
        }
        return $sh;
    }

    function items(Request $request) {
        $rs = parent::items($request, true);

        $planArr = ["","ライトプラン（２万円）","新卒プラン（５万円）","経験者プラン（７万円）"];
        $jp_levelArr = ["","N1","N2","N3","それ以下"];
        $addressArr = ["","日本","日本以外"];

        foreach ($rs['data'] as &$v) {
            $v['plan'] = $planArr[$v['plan']];
            $v['jp_level'] = $jp_levelArr[$v['jp_level']];
            $v['address'] = $addressArr[$v['address']];
        }

        return response()->json($rs);
    }

    public function input(Request $request, $id = 0)
    {
        $data = [];
        $id = (int)$id;
        if($id > 0 && $item = $this->model->where('deleted', 0)->find($id)) {
            $data = $item;
            $planArr = ["","ライトプラン（２万円）","新卒プラン（５万円）","経験者プラン（７万円）"];
            $jp_levelArr = ["","N1","N2","N3","それ以下"];
            $addressArr = ["","日本","日本以外"];

            $data['plan'] = $planArr[$data['plan']];
            $data['jp_level'] = $jp_levelArr[$data['jp_level']];
            $data['address'] = $addressArr[$data['address']];
        }
        return view('admin.' . $this->viewName . '.input', ['data' => $data]);
    }

}
