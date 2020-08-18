<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EmploymentRecordComments\StoreRequest;
use App\Http\Requests\Admin\EmploymentRecordComments\UpdateRequest;
use App\Http\Resources\Admin\EmploymentRecordComments\EmploymentRecordCommentCollection;
use App\Http\Resources\Admin\EmploymentRecordComments\EmploymentRecordCommentResource;
use App\Models\EmploymentRecord;
use App\Models\EmploymentRecordComment;
use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;

class EmploymentRecordCommentsController extends Controller
{
    use ApiResponse;

    public function __construct(Request $request, EmploymentRecordComment $model) {
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
            $data->employment_record_id = $request->input('recordId', '');
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
        $sh['employment_record_id'] = app('request')->input('record_id');
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
            'record'
        ]);
        if (isset($sh['order']) && $sh['order']) {
            $query->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
        }
        $data = $query->orderBy('id', 'desc')->paginate($size);

        return response()->json(new EmploymentRecordCommentCollection($data));
    }

    public function show(EmploymentRecordComment $employmentrecordcomment)
    {
        return $this->success(new EmploymentRecordCommentResource($employmentrecordcomment));
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $record = EmploymentRecord::find($data['employment_record_id']);
        if (!$record) {
            return $this->failed('パラメータエラー。');
        }

        $data['resume_id'] = $record->resume_id;
        $data['admin_id'] = $this->user['id'];

        $result = $this->model->saveItem($data);
        if ($result) {
            $record->update(['updated_at' => date('Y-m-d H:i:s')]);
            return $this->success(new EmploymentRecordCommentResource(EmploymentRecordComment::find($result)))->setStatusCode(201);
        }

        return $this->failed('作成に失敗しました。');
    }

    public function update(UpdateRequest $request, EmploymentRecordComment $employmentrecordcomment)
    {
        $data = $request->validated();
        if ($employmentrecordcomment->saveItem($data)) {
            EmploymentRecord::where('id', $employmentrecordcomment->employment_record_id)->update([
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            return $this->success(new EmploymentRecordCommentResource($employmentrecordcomment));
        }

        return $this->failed('作成に失敗しました。');
    }

    public function destroy(EmploymentRecordComment $employmentrecordcomment)
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
