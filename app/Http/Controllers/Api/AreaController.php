<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Areas;

class AreaController extends BaseController
{
    protected $model = null;


    public function __construct(Request $request, Areas $model) {
        $this->model = $model;
        parent::__construct($request);
    }
    
    public function index(Request $request)
    {
        $sh = [
            'level' => ['conn' => '<=', 'value' => 2],
            'state' => '1',
            'order' => ['field' => 'id', 'sort' => 'asc'],
        ];
        $data = $this->model->getList($sh, true, 0);
        $this->back['data'] = $data;
        return $this->back;
    }
    
}
