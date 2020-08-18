<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EnterpriseEmploymentMemos\StoreRequest;
use App\Http\Requests\Admin\EnterpriseEmploymentMemos\UpdateRequest;
use App\Http\Resources\Admin\EnterpriseEmploymentMemos\EnterpriseEmploymentMemoCollection;
use App\Http\Resources\Admin\EnterpriseEmploymentMemos\EnterpriseEmploymentMemoResource;
use App\Models\EnterpriseEmploymentRecord;
use App\Models\EnterpriseEmploymentMemo;
use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;

class EnterpriseEmploymentMemosController extends Controller
{
    use ApiResponse;

    public function __construct(Request $request, EnterpriseEmploymentMemo $model) {
        parent::__construct();
        $this->model = $model;
    }

    /*
     * 列表渲染
     */
    public function index(Request $request)
    {
        return view('admin.' . $this->viewName . '.index', ['data' => $request->all()]);
    }

    /**
     *
     * @param Request $request
     * @return type
     */
    public function input(Request $request, $id = 0)
    {
        $data = new \stdClass();
        $id = (int)$id;
        if($id > 0 && $item = $this->model->getOne($id)) {
            $data = $item;
            $data->id = $id;
        } else {
            $data->enterprise_employment_record_id = $request->input('recordId', '');
        }
        return view('admin.' . $this->viewName . '.input', ['data' => $data]);
    }

    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function parseSearch($data) {
        $sh = $data;
        $sh['enterprise_employment_record_id'] = app('request')->input('record_id');
        if (!empty($sh['start_date']) && !empty($sh['end_date'])) {
            $sh['created_at'] = ['conn' => 'between', 'value' => [$sh['start_date'] . ' 00:00:00', $sh['end_date'] . ' 23:59:59']];
            unset($sh['start_date']);
            unset($sh['end_date']);
        } elseif (!empty($sh['start_date'])) {
            $sh['created_at'] = ['conn' => '>=', 'value' => $sh['start_date'] . ' 00:00:00'];
            unset($sh['start_date']);
        } elseif (!empty($sh['end_date'])) {
            $sh['created_at'] = ['conn' => '>=', 'value' => $sh['end_date'] . ' 23:59:59'];
            unset($sh['end_date']);
        }

        return $sh;
    }

    public function items(Request $request)
    {
        $size = $request->input('limit', 10);

        $sh = $request->input('sh', []);
        //特殊处理检索字段
        if (method_exists($this, 'parseSearch')) {
            $sh = $this->parseSearch($sh);
        }

        $query = $this->model->whereExtend($sh)->with([
            'record',
            'record.company'
        ]);
        if (isset($sh['order']) && $sh['order']) {
            $query->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
        }
        $data = $query->orderBy('id', 'desc')->paginate($size);

        return response()->json(new EnterpriseEmploymentMemoCollection($data));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $record = EnterpriseEmploymentRecord::find($data['enterprise_employment_record_id']);
        if (!$record) {
            return $this->failed('パラメータエラー。');
        }
        $data['admin_id'] = $this->user['id'];
        $result = $this->model->saveItem($data);
        if ($result) {
            $record->update(['updated_at' => date('Y-m-d H:i:s')]);
            return $this->success(new EnterpriseEmploymentMemoResource(EnterpriseEmploymentMemo::find($result)))->setStatusCode(201);
        }

        return $this->failed('作成に失敗しました。');
    }

    public function update(UpdateRequest $request, EnterpriseEmploymentMemo $enterpriseemploymentmemo)
    {
        $data = $request->validated();
        $data['admin_id'] = $this->user['id'];
        if ($enterpriseemploymentmemo->saveItem($data)) {
            EnterpriseEmploymentRecord::where('id', $enterpriseemploymentmemo->enterprise_employment_record_id)->update([
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return $this->success(new EnterpriseEmploymentMemoResource($enterpriseemploymentmemo));
        }

        return $this->failed('作成に失敗しました。');
    }

    public function destroy(EnterpriseEmploymentMemo $employmentrecordcomment)
    {
        try {
            $employmentrecordcomment->delete();
            return response()->json([
                'status' => 200,
                'msg' => '削除に成功しました。'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 500,
                'msg' => '削除に失敗しました。'
            ]);
        }
    }
}
