<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\EmploymentRecords\EmploymentRecordCollection;
use App\Models\Company;
use App\Models\EmploymentRecord;
use App\Http\Controllers\Admin\BaseController as Controller;
use App\Http\Traits\ApiResponse;
use App\Models\EmploymentRecordComment;
use App\Models\EmploymentRecordCompany;
use App\Models\Job;
use App\Models\Resume;
use Illuminate\Http\Request;

class EmploymentRecordsController extends Controller
{
    use ApiResponse;

    public function __construct(Request $request, EmploymentRecord $model) {
        parent::__construct();
        $this->model = $model;

    }

    public function index(Request $request)
    {
        $resumes = Resume::where('deleted', 0)->select('resume_id', 'name')->get();
        $num = EmploymentRecord::where('updated_at', '<', date('Y-m-d H:i:s', strtotime('-1 day')))->count();
        return view('admin.' . $this->viewName . '.index', ['resumes' => $resumes, 'data' => $request->all(), 'tips_num' => $num]);
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
//        dd($sh);
        $query = $this->model->whereExtend($sh)->with([
            'resume', 'comment', 'companies'
        ]);
        if (isset($sh['order']) && $sh['order']) {
            $query->orderBy($sh['order']['field'], $sh['order']['sort'] ? $sh['order']['sort'] : 'desc');
        }
        $data = $query->orderBy('id', 'desc')->paginate($size);

        return response()->json(new EmploymentRecordCollection($data));
    }

    /**
     *
     * @param Request $request
     * @return type
     */
    public function addJob(Request $request, $id)
    {
        $data = [];
        $id = (int)$id;
        $item = $this->model->getOne($id);
        $jobs = Job::with(['company'])->where('deleted', 0)->select('job_id', 'company_id', 'position')->get();
        if(!$item) {
            return abort(404, 'ページは存在しません。');
        }

        if ($request->isMethod('post')) {
            $data = $request->input('data');
            $data['job_ids'] AND $data['job_ids'] = explode(",", $data['job_ids']);
            if (!$data['job_ids']) {
                return response()->json([
                    'status' => 500,
                    'msg' => 'パラメータエラー。',
                    'data' => $data,
                ]);
            }
            $jobIds = $item->job_ids;
            $newJobs = [];
            foreach ($data['job_ids'] as $job_id) {
                if (!in_array($job_id, $jobIds)) {
                    $jobIds[] = $job_id;
                    $newJobs[] = $job_id;
                }
            }
            $item->job_ids = $jobIds;
            $result = $item->save();

            //添加新增job的公司到记录表中
            if (count($newJobs)) {
                $companyIds = Job::select(\DB::raw('distinct company_id'))
                    ->whereIn('job_id', $newJobs)
                    ->where('company_id', '>', 0)
                    ->pluck('company_id')->toArray();
                $existCompanyIds = EmploymentRecordCompany::where('employment_record_id', $id)->pluck('company_id')->toArray();

                $addCompanyIds = array_diff($companyIds, $existCompanyIds);

                $inserts = [];
                $date = date('Y-m-d H:i:s');
                foreach ($addCompanyIds as $companyId) {
                    $inserts[] = [
                        'employment_record_id' => $id,
                        'company_id' => $companyId,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }
                if (count($addCompanyIds)) {
                    EmploymentRecordCompany::insert($inserts);
                }
            }

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
        return view('admin.' . $this->viewName . '.addJob', ['data' => $item, 'jobs' => $jobs]);
    }


    /**
     *
     * @param Request $request
     * @return type
     */
    public function addCompany(Request $request, $id)
    {
        $data = [];
        $id = (int)$id;
        $item = $this->model->getOne($id);
        $companies = Company::where('deleted', 0)->select('id', 'company_name')->get();
        if(!$item) {
            return abort(404, 'ページは存在しません。');
        }

        if ($request->isMethod('post')) {
            $data = $request->input('data');
            $data['company_ids'] AND $data['company_ids'] = explode(",", $data['company_ids']);
            if (!$data['company_ids']) {
                return response()->json([
                    'status' => 500,
                    'msg' => 'パラメータエラー。',
                    'data' => $data,
                ]);
            }
            $existCompanyIds = EmploymentRecordCompany::where('employment_record_id', $id)
                ->pluck('company_id')
                ->toArray();

            $addCompanyIds = array_diff($data['company_ids'], $existCompanyIds);
            $inserts = [];
            $date = date('Y-m-d H:i:s');
            foreach ($addCompanyIds as $companyId) {
                $inserts[] = [
                    'employment_record_id' => $id,
                    'company_id' => $companyId,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }
            $result = true;
            if (count($addCompanyIds)) {
                $result = EmploymentRecordCompany::insert($inserts);
            }

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
        return view('admin.' . $this->viewName . '.addCompany', ['data' => $item, 'companies' => $companies]);
    }

    public function delCompany(Request $request, $id) {
        $companyId = $request->input('company_id', 0);
        try {
            EmploymentRecordCompany::where('company_id', $companyId)->where('employment_record_id', (int)$id)->delete();
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

    public function delJob(Request $request, $id) {
        $jobId = $request->input('job_id', 0);
        $item = $this->model->getOne($id);
        $jobIds = $item->job_ids;
        if (!in_array($jobId, $jobIds)) {
            return response()->json([
                'status' => 200,
                'msg' => '削除に成功しました。'
            ]);
        }
        unset($jobIds[array_search($jobId, $jobIds)]);


        try {
            $item->job_ids = $jobIds;
            $item->save();
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

    /**
     * 扩展对数据验证
     * @param type $data
     * @param type $msg
     * @return type
     */
    protected function validatorItem($data, &$msg) {
        $valid = [
            'resume_id' => "required|integer|exists:resumes,resume_id|unique:employment_records,resume_id,{$data['id']},id",
        ];
        $tips = [
            'resume_id.required' => '求職者は空にできません。',
            'resume_id.integer' => '求職者形式が正しくありません。',
            'resume_id.exists' => '求職者は存在しません。',
            'resume_id.unique' => '求職者はすでに存在しました。。',
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
            $data = $request->all();
            //验证字段特殊处理检索字段
            if (method_exists($this, 'validatorItem') && $this->validatorItem($data, $msg) == false) {
                return response()->json([
                    'status' => 400,
                    'msg' => $msg
                ]);
            }
            $data['admin_id'] = $this->user['id'];
            $result = $this->model->saveItem($data);
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


    public function destroy(EmploymentRecord $employmentrecord)
    {
        try {
            EmploymentRecordComment::where('employment_record_id', $employmentrecord->id)->delete();
            EmploymentRecordCompany::where('employment_record_id', $employmentrecord->id)->delete();
            $employmentrecord->delete();
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
