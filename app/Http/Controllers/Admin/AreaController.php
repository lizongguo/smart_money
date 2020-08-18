<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\Areas;

class AreaController extends BaseController
{
    protected $model = null;
    protected $data = [];
    protected $sort = 1;


    public function __construct(Areas $model) {
        $this->model = $model;
        parent::__construct();
    }
    
    public function index(Request $request)
    {
        set_time_limit(0);
        $file = public_path() . DIRECTORY_SEPARATOR . 'city.json';
        $dataStr = file_get_contents($file);
        $dataArr = json_decode($dataStr, true);
        $level = 1;
        $this->dealData($dataArr, $level, 0);
        die('导入完毕');
    }
    
    protected function dealData($data,  $lavel, $parent_id = 0) {
        foreach($data as $id => $item) {
            if(is_array($item)) {
                $area_name = $item['name'];
            }else{
                $area_name = $item;
            }
            $this->data[$id] = [
                'id'        => $id,
                'area_name' => $area_name,
                'parent_id' => $parent_id,
                'level'     => $lavel,
                'sort'      => $this->sort++,
                'path'      => (isset($this->data[$parent_id]) ? $this->data[$parent_id]['path'] : ',') . $parent_id . ',',
            ];
            $this->model->insertItems($this->data[$id]);
            if(isset($item['child']) && count($item['child']) > 0) {
                $this->dealData($item['child'], $lavel + 1, $id);
            }
        }
    }
}
