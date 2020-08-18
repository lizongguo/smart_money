<?php
/**
 * Created by NetBeans
 * User: yutlong
 * Date: 2019/4/1 0029
 * Time: 上午 10:48
 */

namespace App\Http\Controllers\Company;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Models\JobRecord;
use App\Models\Job;
use DB;
use App\Models\JobRecordMemo;
use App\Models\JobRecordStatus;
use App\Models\Experience;
use App\Models\Company;
use App\Models\Resume;

class JobRecordController extends BaseController
{
    public function __construct(Request $request, Job $model, JobRecord $jobRecord, JobRecordMemo $jobRecordMemo, JobRecordStatus $jobRecordStatus) {
        parent::__construct();
        $this->model = $model;
        $this->jobRecord = $jobRecord;
        $this->jobRecordMemo = $jobRecordMemo;
        $this->jobRecordStatus = $jobRecordStatus;

        //获取全部的店铺
        view()->share('jobInfo', config("code.job"));
        view()->share('resumeInfo', config("code.resume"));
    }

    protected function parseSearch($data) {
        $sh = $data;
        if (!empty($data['position'])) {
            $sh['position'] = ['conn' => 'lk', 'value' => $data['position']];
        }
        //$sh['record_count'] = ['conn' => '>', 'value' => 0];
        return $sh;
    }

    protected function parseSearchUser(&$data) {
        $resumeInfo = config("code.resume");
        $sh = [];

        if (!empty($data['jp_level_2_arr'])) {
            $data['jp_level_2'] = explode(',', $data['jp_level_2_arr']);
            $sh['resumes.jp_level'] = ['conn' => 'in', 'value' => $data['jp_level_2']];
        }

        if (!empty($data['nationality_id'])) {
            if ($resumeInfo['nationality'][$data['nationality_id']] != 'その他') {
                $sh['resumes.nationality_id'] = ['conn' => '=', 'value' => $data['nationality_id']];
            } else {
                $sh['resumes.nationality'] = ['conn' => '=', 'value' => $data['nationality']];
            }
        }
        if (!empty($data['sex_arr'])) {
            $data['sex'] = explode(',', $data['sex_arr']);
            $sh['resumes.sex'] = ['conn' => 'in', 'value' => $data['sex']];
        }
        if (!empty($data['age_start']) && !empty($data['age_end'])) {
            $age_start = date("Y-m-d", strtotime("-{$data['age_start']} year"));
            $age_end = date("Y-m-d", strtotime("-{$data['age_end']} year"));
            $sh['resumes.birthday'] = ['conn' => 'between', 'value' => [$age_end, $age_start]];
        } elseif (!empty($data['age_start'])) {
            $age_start = date("Y-m-d", strtotime("-{$data['age_start']} year"));
            $sh['resumes.birthday'] = ['conn' => 'between', 'value' => ["0001-01-01", $age_start]];
        } elseif (!empty($data['age_end'])) {
            $age_end = date("Y-m-d", strtotime("-{$data['age_end']} year"));
            $sh['resumes.birthday'] = ['conn' => '>=', 'value' => $age_end];
        }
        if (!empty($data['address_arr'])) {
            $data['address'] = explode(',', $data['address_arr']);
            $sh['resumes.address'] = ['conn' => 'in', 'value' => $data['address']];
        }
        if (!empty($data['employment_status_arr'])) {
            $data['employment_status'] = explode(',', $data['employment_status_arr']);
            $sh['resumes.employment_status'] = ['conn' => 'in', 'value' => $data['employment_status']];
        }
        if (!empty($data['science_arts_arr'])) {
            $data['science_arts'] = explode(',', $data['science_arts_arr']);
            $sh['resumes.science_arts'] = ['conn' => 'in', 'value' => $data['science_arts']];
        }
        if (!empty($data['final_education_arr'])) {
            $data['final_education'] = explode(',', $data['final_education_arr']);
            $sh['resumes.final_education'] = ['conn' => 'in', 'value' => $data['final_education']];
        }

        if ($data['job_id']) {
            $sh['job_record.job_id'] = ['conn' => '=', 'value' => $data['job_id']];
        }

        if ($data['type']) {
            $sh['job_record.type'] = ['conn' => '=', 'value' => $data['type']];
        }

        if ($data['not_in_UserId']) {
            $sh['resumes.user_id'] = ['conn' => 'notin', 'value' => $data['not_in_UserId']];
        }

        //$sh['resumes.user_id'] = ['conn' => '>', 'value' => 0];

        return $sh;
    }

    public function index(Request $request, $type = "record")
    {
        $menu_active = $type;
        view()->share('menu_active', $menu_active);

        $data = $request->input("sh");
        $data['company_id'] = $this->user->id;
        $sh = $this->parseSearch($data);

        if ($type == "record") {
            $sh['order'] = ['field' => 'record_count_new', 'sort' => 'desc'];
        }

        $list = $this->model->getList($sh, false, $this->pageCount, "*");

        $companyObj = new Company();
        $companyInfo = $companyObj->find($this->user->id);

        $date = date("Y-m-d 00:00:00");
        $todayUse = $this->jobRecord->where('created_at', '>', $date)->where('type', 2)->where('company_id', $this->user->id)->count();

        $today_residue= $companyInfo['resume_count'] - $todayUse;
        $companyInfo['today_residue'] = $today_residue > 0 ? $today_residue : 0;

        return view('company.' . $this->viewName . '.index', ['list' => $list, 'type' => $type, 'companyInfo' => $companyInfo]);
    }

    public function recordUserList(Request $request, $type = 'record', $jobId = 0)
    {
        //jobinfo
        $jobInfo = $this->model->find($jobId);
        if ($jobInfo['company_id'] != $this->user->id) {
            return redirect(route('company.index.index'));
        }

        $allData = [
            'nationality_id' => '',
            'jp_level_2' => [],
            'nationality' => '',
            'sex' => [],
            'age_start' => '',
            'age_end' =>  '',
            'address' => [],
            'employment_status' =>  [],
            'science_arts' =>  [],
            'final_education' => [],
            'search_text_str' => '',
        ];

        $data = $request->all();
        foreach ($allData as $k => $v) {
            if (!isset($data[$k])) {
                $data[$k] = $v;
            }
        }
        $data['job_id'] = $jobId;
        $data['type'] = $type == 'record' ? 1 : 2;

        $sh = $this->parseSearchUser($data);

        $list = $this->jobRecord->getUserList($sh, true, $this->pageCount, ['job_record.status_company', 'job_record.status_user', 'job_record.status_company_time', 'job_record.read_company', 'job_record.read_company_time', 'job_record.id','job_record.created_at as job_record_created_at', "resumes.*"]);

        $statusArr = [];
        $accountCodeArr = [];
        foreach ($list as &$v) {
            $v['account_code'] = $v['account_code'] ? $v['account_code'] : $v['user_id'];
            $statusArr[$v['id']] = $v['status_company'];
            $accountCodeArr[$v['id']] = $v['account_code'];
        }

        $updateJob = [
            'job_id' => $jobId,
            'record_count_new' => 0,
        ];

        $jobRs = $this->model->saveItem($updateJob);

        $resumeObj = new Resume();
        $list = $resumeObj->array_to_str($list);

        return view('company.' . $this->viewName . '.record_user_list', ['list' => $list, 'type' => $type, 'jobInfo' => $jobInfo, 'data' => $data, 'statusArr' => $statusArr, 'accountCodeArr' => $accountCodeArr]);
    }

    public function memo_add(Request $request)
    {
        $data = $request->only(['record_id', 'status_user', "memo"]);
        $rs = [
            'status' => '200',
            'msg' => config('code.alert_msg.JobRecord.memo_save_success'),
        ];

        if (!$data['record_id'] || !$data['status_user']) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
            return response()->json($rs);
        }

        if (is_array($data['record_id']) && count($data['record_id']) >= 1) {

        } else {
            $data['record_id'] = [$data['record_id']];
        }

        $date = date("Y-m-d H:i:s");
        foreach($data['record_id'] as $v) {
            $info = $this->jobRecord->find($v);
            if ($data['status_user'] == 2) {
                if ($info['status_company'] != 1) {
                    continue;
                }
            } elseif (in_array($data['status_user'], [3])) {
                if ($info['status_company'] != 2 && $info['status_company'] != 1) {
                    continue;
                }
                $data['memo'] = config("code.record.status_company")[$data['status_user']];
            } elseif ($data['status_user'] > 5) {
                if ( !($info['status_company'] > 5 || $info['status_company'] == 4) ) {
                    continue;
                }
            }
            $insertMemo = [
                'record_id' => $v,
                'status_company' => $data['status_user'],
                'type' => 2,
                'memo' => $data['memo'],
            ];
            $memoId = $this->jobRecordMemo->saveItem($insertMemo);

            $status_user = 0;
            if ($data['status_user'] == 3) {
                $insertStatus = [
                    'record_id' => $v,
                    'status_company' => $data['status_user'],
                    'type' => 2,
                    'title' => config("code.record.status_company")[$data['status_user']],
                    'content' => $data['memo'],
                ];

                $insertStatus['title'] = "";
                $insertStatus['content'] = "是非書類選考に進めていきたいですので、履歴書と職務経歴書の開示をお願いします。";

                $statusId = $this->jobRecordStatus->saveItem($insertStatus);

                $status_user = 2;
            }

            $update = [
                'id' => $v,
                'status_company' => $data['status_user'],
                'status_company_time' => $date,
                'read_user' => $data['status_user'] == 3 ? DB::raw('read_user + 1') : 0,
                //'read_company' => 0,
                //'read_company_time' => $date,
            ];
            if ($status_user == 2) {
                $update['status_user'] = $status_user;
                $update['status_user_time'] = $date;
            }
            $id = $this->jobRecord->saveItem($update);

            if ($id === false || $memoId === false || $statusId === false) {
                $rs['status'] = '500';
                $rs['msg'] = config('code.alert_msg.system.error');
                break;
            }
        }

        return response()->json($rs);
    }

    public function status_add(Request $request)
    {
        $data = $request->only(['record_id', 'status_company', 'title', "content"]);
        $rs = [
            'status' => '200',
            'msg' => config('code.alert_msg.JobRecord.status_save_success'),
        ];

        $date = date("Y-m-d H:i:s");
        if (isset($data['status_company']) && in_array($data['status_company'], [3, 4])) {
            $data['title'] = config("code.record.status_company")[$data['status_company']];

            $updateRecord = [
                'id' => $data['record_id'],
                'status_company' => $data['status_company'],
                'status_company_time' => $date,
                'read_user' => DB::raw('read_user + 1'),
                //'read_company_time' => $date,
                //'read_company' => 0,
            ];
            if ($data['status_company'] == 4) {
                //$updateRecord['finish_status'] = 1;
            }

            $insertMemo = [
                'record_id' => $data['record_id'],
                'status_company' => $data['status_company'],
                'type' => 2,
                'memo' => $data['title'],
            ];
            $memoId = $this->jobRecordMemo->saveItem($insertMemo);

        } else {
            $updateRecord = [
                'id' => $data['record_id'],
                'read_user' => DB::raw('read_user + 1'),
                //'read_company_time' => $date,
                //'read_company' => 0,
            ];
        }

        $id = $this->jobRecord->saveItem($updateRecord);

        $insertStatus = [
            'record_id' => $data['record_id'],
            'status_company' => $data['status_company'],
            'type' => 2,
            'title' => $data['title'],
            'content' => $data['content'],
        ];
        $statusId = $this->jobRecordStatus->saveItem($insertStatus);

        if ($id === false || $statusId === false) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
        }

        $sh = [
            'record_id' => $data['record_id'],
        ];
        $list = $this->jobRecordStatus->getList($sh, true);

        $recordInfo = $this->jobRecord->find($data['record_id']);

        $rs['list'] = $list;
        $rs['recordInfo'] = $recordInfo;

        return response()->json($rs);
    }

    public function read(Request $request)
    {
        $data = $request->only(['record_id', 'status_user', "memo"]);
        $rs = [
            'status' => '200',
            'msg' => '',
        ];

        if (!$data['record_id']) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
            return response()->json($rs);
        }

        $date = date("Y-m-d H:i:s");
        $insertMemo = [
            'id' => $data['record_id'],
            'read_company' => 0,
            'read_company_time' => $date,
        ];
        $id = $this->jobRecord->saveItem($insertMemo);

        if ($id === false) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
        }

        return response()->json($rs);
    }

    public function user_info(Request $request, $rid = 0)
    {
        $recordInfo = $this->jobRecord->getUserList(['job_record.id' => $rid, 'job_record.company_id' => $this->user->id ], true, $this->pageCount, ['job_record.status_company', 'job_record.status_company_time', 'job_record.read_company', 'job_record.read_company_time', 'job_record.id', 'job_record.user_id', 'job_record.status_user', 'job_record.resume_status', 'job_record.note', 'job_record.created_at as job_record_created_at', "job_record.job_id", "resumes.*"]);

        $resumeObj = new Resume();
        $recordInfo = $resumeObj->array_to_str($recordInfo);
        $recordInfo = $recordInfo[0];

        if (!$recordInfo) {
            exit;
        }

        $jobInfo = $this->model->find($recordInfo['job_id']);

        $experience = new Experience();
        $uInfo = $experience->where('user_id', $recordInfo['user_id'])->first();
        if ($recordInfo['status_user'] > 2 && $recordInfo['status_user'] != 4) {
            $uInfo = $experience->dealItemData($uInfo);
            //print_r($uInfo);exit;
        } else {
            $uInfo = [
                'video_url' => $uInfo['video_url'],
            ];
        }
        $rs = [
            'recordInfo' => $recordInfo,
            'jobInfo' => $jobInfo,
            'uInfo' => $uInfo,
        ];
        return view('company.' . $this->viewName . '.user_info', $rs);
    }

    public function resume_info(Request $request, $uid = 0)
    {
        $resumeObj = new Resume();
        $recordInfo = $resumeObj->where('user_id', $uid)->first();

        $list = [$recordInfo];
        $list = $resumeObj->array_to_str($list);

        $recordInfo = $list[0];
        
        $rs = [
            'recordInfo' => $recordInfo,
        ];
        return view('company.' . $this->viewName . '.resume_info', $rs);
    }

    public function status_list(Request $request, $rid = 0)
    {
        if ($request->isMethod('post')) {
            $record_id = $request->input("record_id");
            $sh = [
                'record_id' => $record_id,
            ];
            $list = $this->jobRecordStatus->getList($sh, true);

            $recordInfo = $this->jobRecord->getUserList(['job_record.id' => $record_id], true, $this->pageCount, ['job_record.status_company', 'job_record.status_company_time', 'job_record.read_company', 'job_record.read_company_time', 'job_record.id', 'job_record.finish_status', 'job_record.created_at as job_record_created_at', "job_record.job_id", "resumes.*"]);

            $resumeObj = new Resume();
            $recordInfo = $resumeObj->array_to_str($recordInfo);

            $jobInfo = $this->model->find($recordInfo[0]['job_id']);

            $rs = [
                'status' => '200',
                'msg' => 'ok',
                'list' => $list,
                'recordInfo' => $recordInfo[0],
                'jobInfo' => $jobInfo,
            ];

            return response()->json($rs);
        }

        return view('company.' . $this->viewName . '.status_list', ['record_id' => $rid]);
    }

    public function memo_list(Request $request)
    {
        $record_id = $request->input("record_id");
        $sh = [
            'record_id' => $record_id,
            'type' => 2,
        ];
        $list = $this->jobRecordMemo->getList($sh, true);

        $info = $this->jobRecord->find($record_id);
        $status = config("code.record.status_company");

        $statusList = [];
        $buttonStatus = 1;
        if ($info['status_company'] == 1) {
            $statusList[2] = $status[2];
            $statusList[3] = $status[3];
        } elseif (in_array($info['status_company'], [2])) {
            $statusList[3] = $status[3];
        } elseif (in_array($info['status_company'], [3])) {
            $buttonStatus = 0;
            $statusList = [];
        } else {
            $buttonStatus = 0;
            $statusList = $status;
            unset($statusList[1],$statusList[2],$statusList[3],$statusList[4],$statusList[5]);
        }

        $rs = [
            'status' => '200',
            'msg' => 'ok',
            'list' => $list,
            'statusList' => $statusList,
            'buttonStatus' => $buttonStatus,
        ];

        return response()->json($rs);
    }

    public function scoutUser(Request $request, $jobId = 0)
    {
        //jobinfo
        $jobInfo = $this->model->find($jobId);

        $search = [
            'job_record.job_id' => $jobId,
        ];
        $jobRecordList = $this->jobRecord->getList($search, true);
        $useUserId = [];
        foreach($jobRecordList as $v) {
            $useUserId[] = $v['user_id'];
        }

        $allData = [
            'nationality_id' => '',
            'jp_level_2' => '',
            'nationality' => '',
            'sex' => [],
            'age_start' => '',
            'age_end' =>  '',
            'address' => [],
            'employment_status' =>  [],
            'science_arts' =>  [],
            'final_education' => [],
            'search_text_str' => '',
        ];

        $data = $request->all();
        foreach ($allData as $k => $v) {
            if (!isset($data[$k])) {
                $data[$k] = $v;
            }
        }

        $useUserId[] = 0;
        $data['not_in_UserId'] = $useUserId;
        $sh = $this->parseSearchUser($data);
        $sh['order'] = ['field' => 'user_id', 'sort' => 'desc'];

        $resumeObj = new Resume();
        $list = $resumeObj->getList($sh, false, $this->pageCount, '*');

        $list = $resumeObj->array_to_str($list);

        return view('company.' . $this->viewName . '.scout_user_list', ['list' => $list, 'jobInfo' => $jobInfo, 'data' => $data]);
    }

    //scout user
    public function selectUser(Request $request)
    {
        $data = $request->only(['record_id', 'job_id']);
        $rs = [
            'status' => '200',
            'msg' => config('code.alert_msg.JobRecord.scout_success'),
        ];

        if (!$data['record_id'] || !$data['job_id']) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
            return response()->json($rs);
        }

        $companyObj = new Company();
        $companyInfo = $companyObj->find($this->user->id);

        $date = date("Y-m-d 00:00:00");
        $todayUse = $this->jobRecord->where('created_at', '>', $date)->where('type', 2)->where('company_id', $this->user->id)->count();

        $today_residue = $companyInfo['resume_count'] - $todayUse;

        if (count($data['record_id']) > $today_residue) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.JobRecord.scout_overstep');
            return response()->json($rs);
        }

        $resumeInfo = config("code.resume");
        $userCount = 0;

        foreach ($data['record_id'] as $v) {
            $info = $this->jobRecord->where('job_id', $data['job_id'])->where('user_id', $v)->where('finish_status', 0)->first();
            if ($info) {
                continue;
            }

            $date = date("Y-m-d H:i:s");
            $insert = [
                'user_id' => $v,
                'job_id' => $data['job_id'],
                'company_id' => $this->user->id,
                'status_user' => 2,
                'status_company' => 3,
                'read_user' => 1,
                'status_user_time' => $date,
                'status_company_time' => $date,
                'type' => 2,
                'note' => '',
            ];

            $jobRecordObj = new JobRecord();
            $id = $jobRecordObj->saveItem($insert);

            $insertMemo1 = [
                'record_id' => $id,
                'status_user' => 2,
                'type' => 1,
                'memo' => config("code.record.status_user")[2],
            ];

            $jobRecordMemo = new JobRecordMemo();
            $memoId1 = $jobRecordMemo->saveItem($insertMemo1);

            $insertMemo2 = [
                'record_id' => $id,
                'status_company' => 3,
                'type' => 2,
                'memo' => config("code.record.status_company")[3],
            ];
            $obj = new JobRecordMemo();
            $memoId2 = $obj->saveItem($insertMemo2);


            $jobRecordStatusObj = new JobRecordStatus();
            $insertStatus = [
                'record_id' => $id,
                'status_company' => 3,
                'type' => 2,
                'title' => config("code.record.status_company")[3],
            ];
            $statusId = $jobRecordStatusObj->saveItem($insertStatus);

            $updateJob = [
                'job_id' => $data['job_id'],
                'scout_count' => DB::raw('scout_count + 1'),
            ];

            $jobObj = new Job();
            $jobRs = $jobObj->saveItem($updateJob);

            $favoriteObj = new Favorite();
            $favoriteInfo = $favoriteObj->where("user_id", $v)->where('job_id', $data['job_id'])->first();
            if ($favoriteInfo) {
                $favoriteObj->where('id', $favoriteInfo['id'])->delete();
            }

            $userCount += 1;
        }

        if ($userCount) {
            $updateCompany = [
                'id' => $companyInfo['id'],
                'record_count' => DB::raw("resume_count - {$userCount}"),
            ];
            $companyObj = new Company();
            $companyObj->saveItem($updateCompany);
        }

        return response()->json($rs);
    }

    function video(Request $request, $rid = 0) {
        $recordInfo = $this->jobRecord->find($rid);
        $experience = new Experience();
        $uInfo = $experience->where('user_id', $recordInfo['user_id'])->first();

        $video_url = "";
        if ($recordInfo['resume_status'] == 1 && $recordInfo['company_id'] == $this->user->id && $uInfo['video_url']) {
            $video_url = $uInfo['video_url'];
        }

        return view('company.' . $this->viewName . '.video', ['video_url' => $video_url]);
    }

}
