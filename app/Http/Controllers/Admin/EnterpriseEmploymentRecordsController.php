<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Http\Resources\Admin\EnterpriseEmploymentRecords\EnterpriseEmploymentRecordCollection;
use App\Http\Resources\Admin\EnterpriseEmploymentRecords\EnterpriseEmploymentRecordResource;
use App\Models\EnterpriseEmploymentMemo;
use App\Models\EnterpriseEmploymentRecord;
use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Resume;
use Illuminate\Http\Request;

class EnterpriseEmploymentRecordsController extends Controller
{
    use ApiResponse;

    public function __construct(Request $request, EnterpriseEmploymentRecord $model) {
        parent::__construct();
        $this->model = $model;
        $companies = Company::where('deleted', 0)->select('id', 'company_name')->get();
        $resumes = Resume::where('deleted', 0)->select('resume_id', 'name')->get();
        view()->share('companies', $companies);
        view()->share('resumes', $resumes);

    }

    public function index(Request $request)
    {
        $num = EnterpriseEmploymentRecord::where('updated_at', '<', date('Y-m-d H:i:s', strtotime('-1 day')))->count();
        return view('admin.' . $this->viewName . '.index', ['data' => $request->all(), 'tips_num' => $num]);
    }

    /**
     * 扩展对数据查询接口处理
     * @param type $data
     * @param type $msg
     * @return type
     */

    protected function parseSearch($data) {
        $sh = $data;

        if (isset($sh['day']) && $sh['day'] > 0) {
            $sh['updated_at'] = ['conn' => '<=', 'value' => date('Y-m-d H:i:s', strtotime("-{$sh['day']} day"))];
        }
        unset($sh['day']);
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
            'resume', 'comment', 'memo', 'company', 'employment_record'
        ]);
        if (isset($sh['order']) && $sh['order']) {
            $query->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
        }
        $data = $query->orderBy('id', 'desc')->paginate($size);

        return response()->json(new EnterpriseEmploymentRecordCollection($data));
    }

    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'company_id' => "required|exists:company,id",
            'resume_id' => "sometimes|array",
            'resume_id.*' => [
                'exists:resumes,resume_id'
            ],
        ];
        $tips = [
            'company_id.required' => '企業は空にできません。',
            'company_id.exists' => '企業は存在しません。',
            'resume_id.required' => '求職者は空にできません。',
            'resume_id.array' => '求職者のフォーマットの入力が間違っています。',
            'resume_id.min' => '少なくとも一つの求職者を選択します。',
            'resume_id.*.exists' => '求職者は存在しません。',
        ];

        $validator = \Validator::make($data, $valid, $tips);
        if ($validator->fails()) {
            $msg = $validator->errors()->all();
            return false;
        }
        return true;
    }

    /**
     * @param Request $request
     * @return type
     */
    public function input(Request $request)
    {
        $resumes = Resume::where('deleted', 0)->select('resume_id', 'name')->get();

        if ($request->isMethod('post')) {
            $data              = $request->all();
            $data['resume_id'] = preg_split('/,/', $data['resume_id'], -1, 1);
            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorItem') && $this->validatorItem($data, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg'    => $msg
                ]);
            }
            $data['admin_id'] = $this->user['id'];
            $inserts          = [];
            $date             = date('Y-m-d H:i:s');
            if (count($data['resume_id']) < 1) {
                $inserts[] = [
                    'company_id' => $data['company_id'],
                    'admin_id'   => $this->user['id'],
                    'resume_id'  => 0,
                    'status'     => 0,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            } else {
                $existIds = EnterpriseEmploymentRecord::where('company_id', $data['company_id'])
                    ->whereIn('resume_id', $data['resume_id'])
                    ->pluck('resume_id')->toArray();

                $insertIds = array_diff($data['resume_id'], $existIds);


                foreach ($insertIds as $item) {
                    $inserts[] = [
                        'company_id' => $data['company_id'],
                        'admin_id'   => $this->user['id'],
                        'resume_id'  => $item,
                        'status'     => 1,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
            }
            $result = true;
            if (count($inserts)) {
                $result = EnterpriseEmploymentRecord::insert($inserts);
            }
            //save success
            if($result === false) {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存に失敗しました。',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'msg' => '保存に成功しました。'
                ]);
            }
        }
        return view('admin.' . $this->viewName . '.input', ['resumes' => $resumes]);
    }

    public function show(EnterpriseEmploymentRecord $enterpriseemploymentrecord)
    {
        return $this->success(new EnterpriseEmploymentRecordResource($enterpriseemploymentrecord));
    }

    /*
     * 修改状态
     */
    public function state(Request $request, $id)
    {
        $item = $this->model->find($id);
        if (!$item) {
            return abort('404', 'ページは存在しません。');
        }

        if ($request->isMethod('post')) {
            $data = $request->input('data');

            if (!isset(EnterpriseEmploymentRecord::STATUS_TEXT[$data['status']])) {
                return response()->json([
                    'status' => 500,
                    'msg' => 'パラメータエラー。',
                    'data' => $data,
                ]);
            }
            $item->status = $data['status'];
            $result = $item->save();
            //save success
            if($result === false) {
                return response()->json([
                    'status' => 500,
                    'msg' => '保存に失敗しました。',
                    'data' => $data
                ]);
            }
            return response()->json([
                'status' => 200,
                'msg' => '保存に成功しました。'
            ]);
        }
        return view('admin.' . $this->viewName . '.state', ['data' => $item, 'status' => EnterpriseEmploymentRecord::STATUS_TEXT]);
    }

    public function destroy(EnterpriseEmploymentRecord $enterpriseemploymentrecord)
    {
        try {
            EnterpriseEmploymentMemo::where('enterprise_employment_record_id', $enterpriseemploymentrecord->id)->delete();
            $enterpriseemploymentrecord->delete();
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
