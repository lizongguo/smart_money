<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Resume;
use App\Models\Favorite;
use App\Models\JobRecord;
use App\Models\JobRecordMemo;
use App\Models\JobRecordStatus;
use App\Models\Experience;
use App\Models\Company;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Mail;

class JobRecordController extends BaseController
{
    public function __construct(Job $model, JobRecord $jobRecord, Resume $resume, Favorite $favorite, JobRecordMemo $jobRecordMemo, JobRecordStatus $jobRecordStatus) {
        parent::__construct();
        $this->job = $model;
        $this->jobRecord = $jobRecord;
        $this->resume = $resume;
        $this->favorite = $favorite;
        $this->jobRecordMemo = $jobRecordMemo;
        $this->jobRecordStatus = $jobRecordStatus;

        $this->recordInfo = config("code.record");

        view()->share('recordInfo', $this->recordInfo);
        view()->share('pageTitle', $this->pageTitle);
    }

    function record(Request $request, $jobId = 0) {
        if ($request->isMethod('post')) {
            $data = $request->only(['job_id', 'note', "resume_status"]);

            $rs = [
                'status' => '200',
                'msg' => config('code.alert_msg.JobRecord.record_success'),
            ];

            $recordInfo = $this->jobRecord->where("user_id", $this->user->id)->where('job_id', $data['job_id'])->where("finish_status", 0)->first();
            if ($recordInfo) {
                if ($recordInfo['type'] == 1) {
                    $msg = config('code.alert_msg.web.record_job_exist');
                } elseif ($recordInfo['type'] == 2) {
                    $msg = config('code.alert_msg.web.scout_job_exist');
                }
                $rs = [
                    'status' => '400',
                    'msg' => $msg,
                ];
                return response()->json($rs);
            }

            $jobInfo = $this->job->find($data['job_id']);

            $date = date("Y-m-d H:i:s");
            $insert = [
                'user_id' => $this->user->id,
                'job_id' => $data['job_id'],
                'company_id' => $jobInfo['company_id'],
                'status_user' => 1,
                'status_company' => 1,
                //'read_company' => 1,
                'status_user_time' => $date,
                'status_company_time' => $date,
                'type' => 1,
                'note' => $data['note'],
                'resume_status' => $data['resume_status'],
            ];

            $id = $this->jobRecord->saveItem($insert);

            $updateResume = [
                'pr_other' => $data['note'],
            ];

            $this->resume->where('user_id', $this->user->id)->update($updateResume);

            $insertMemo1 = [
                'record_id' => $id,
                'status_user' => 1,
                'type' => 1,
                'memo' => $this->recordInfo['status_user'][1],
            ];

            $memoId1 = $this->jobRecordMemo->saveItem($insertMemo1);

            $insertMemo2 = [
                'record_id' => $id,
                'status_company' => 1,
                'type' => 2,
                'memo' => $this->recordInfo['status_company'][1],
            ];
            $obj = new JobRecordMemo();
            $memoId2 = $obj->saveItem($insertMemo2);

            $updateJob = [
                'job_id' => $data['job_id'],
                'record_count' => DB::raw('record_count + 1'),
                'record_count_new' => DB::raw('record_count_new + 1'),
            ];

            $jobRs = $this->job->saveItem($updateJob);

            $favoriteInfo = $this->favorite->where("user_id", $this->user->id)->where('job_id', $data['job_id'])->first();
            if ($favoriteInfo) {
                $this->favorite->where('id', $favoriteInfo['id'])->delete();
            }

            if ($id === false || $memoId1 === false || $memoId2 === false || $jobRs === false) {
                $rs['status'] = '500';
                $rs['msg'] = config('code.alert_msg.system.error');
            }

            $resumeInfo = config("code.resume");

            $working_form = explode(',', $jobInfo['working_form']);
            $working_form_str = "";
            foreach ($working_form as $v) {
                $working_form_str .= $resumeInfo['working_form'][$v] . "/";
            }
            $working_form_str = mb_substr($working_form_str, 0, mb_strlen($working_form_str)-1);

            $prefecture = "";
            if (isset($jobInfo['prefecture']) && $jobInfo['prefecture']) {
                $prefectureArr = explode(',', $jobInfo['prefecture']);
                foreach ($prefectureArr as $v) {
                    $prefecture .= $resumeInfo['country_city'][$v] . "/";
                }
            }
            $prefecture = mb_substr($prefecture, 0, mb_strlen($prefecture)-1);

            $resume = $this->resume->where("user_id", $this->user->id)->first();
            $message = [
                'account_code' => $jobInfo['account_code'],
                'position' => $jobInfo['position'],
                'working_form' => $working_form_str,
                'prefecture' => $prefecture,
                'name' => $resume['name'],
                'email' => $resume['email'],
                'cell_phone' => $resume['cell_phone'],
            ];

            $to = config("code.mail.findjapanjob.to");
            $cc = config("code.mail.findjapanjob.cc");
            $subject = "Findjapanjobから応募を受けました";
            Mail::send(
                'emails.record_admin',
                ['content' => $message],
                function ($message) use($to, $cc, $subject) {
                    $message->to($to)->cc($cc)->subject($subject);
                }
            );

            $to = $this->user->email;
            $subject = "ご応募いただきどうもありがとうございます";
            Mail::send(
                'emails.record_user',
                ['content' => $message],
                function ($message) use($to, $subject) {
                    $message->to($to)->subject($subject);
                }
            );

            return response()->json($rs);
        }

        $resumeInfo = config("code.resume");
        $jobInfo = $this->job->find($jobId);
        if (!$jobInfo) {
            return redirect(route('web.index.index'));
        }

        $working_form = explode(',', $jobInfo['working_form']);
        $working_form_str = "";
        foreach ($working_form as $v) {
            $working_form_str .= $resumeInfo['working_form'][$v] . "/";
        }
        $working_form_str = mb_substr($working_form_str, 0, mb_strlen($working_form_str)-1);

        $prefecture = "";
        if (isset($jobInfo['prefecture']) && $jobInfo['prefecture']) {
            $prefectureArr = explode(',', $jobInfo['prefecture']);
            foreach ($prefectureArr as $v) {
                $prefecture .= $resumeInfo['country_city'][$v] . "/";
            }
        }

        $prefecture = mb_substr($prefecture, 0, mb_strlen($prefecture)-1);

        $jobInfo['yearly_income_low'] = $this->updateIncome($jobInfo['yearly_income_low'], $resumeInfo['wage_arr_1']);
        $jobInfo['yearly_income_up'] = $this->updateIncome($jobInfo['yearly_income_up'], $resumeInfo['wage_arr_1']);
        $yearly_income_up = $resumeInfo["wage_arr_1"][$jobInfo['yearly_income_up']];
        $yearly_income_low = $resumeInfo["wage_arr_1"][$jobInfo['yearly_income_low']];

        $jobInfo['working_form_str'] = $working_form_str;
        $jobInfo['prefecture_str'] = $prefecture;
        $jobInfo['yearly_income_str'] = "年俸{$yearly_income_low}~{$yearly_income_up}";

        $resume = $this->resume->where("user_id", $this->user->id)->first();
        if ($resume['birthday'] && $resume['birthday'] != "0000-00-00") {
            $age_str = floor((time() - strtotime($resume['birthday'])) / (3600 * 24 * 365)) . "歳";
        } else {
            $age_str = "不明";
        }
        $resume['age_str'] = $age_str;

        //短视频
        $experience = new Experience();
        $experienceInfo = $experience->where('user_id', $this->user->id)->first();

        $record = 0;
        $recordInfo = $this->jobRecord->where("user_id", $this->user->id)->where('job_id', $jobId)->where("finish_status", 0)->first();
        $record = $recordInfo ? $recordInfo['type'] : 0;

        $resume['recordStatus'] = $record;

        $resume['experienceInfo'] = $experienceInfo;

        return view('web.' . $this->viewName . '.record', ['jobInfo' => $jobInfo, 'data' => $resume, 'resumeInfo' => $resumeInfo]);
    }

    function updateIncome($key, $data) {
        if (!isset($data[$key])) {
            $temK = 0;
            foreach ($data as $k => $v) {
                if ($key <= $k) {
                    break;
                }
                $temK = $k;
            }

            return $temK;
        } else {
            return $key;
        }
    }

    public function index(Request $request, $type = 'record')
    {
        $menu_active = $type;
        view()->share('menu_active', $menu_active);

        if ($type == 'record') {
            $type_int = 1;
        } else {
            $type_int = 2;
        }
        $sh = [
            'job_record.user_id' => $this->user->id,
            'job_record.type' => $type_int,
        ];
        $list = $this->jobRecord->getList($sh, false, $this->pageCount, ['jobs.job_id', 'jobs.job_name', 'jobs.position', 'job_record.status_user', 'job_record.status_user_time', 'job_record.read_user', 'job_record.read_user_time', 'job_record.id']);

        return view('web.' . $this->viewName . '.index', ['list' => $list, 'type' => $type]);
    }

    public function memo_list(Request $request)
    {
        $record_id = $request->input("record_id");
        $sh = [
            'record_id' => $record_id,
            'type' => 1,
        ];
        $list = $this->jobRecordMemo->getList($sh, true);

        $recordInfo = $this->jobRecord->find($record_id);
        $status = config("code.record.status_user");

        $buttonStatus = 1;
        if ($recordInfo['status_user'] == 1) {
            $statusList = [];
            $buttonStatus = 0;
        } elseif (in_array($recordInfo['status_user'], [2])) {
//            $statusList[3] = $status[3];
//            $statusList[4] = $status[4];
            $statusList = [];
            $buttonStatus = 0;
        } elseif (in_array($recordInfo['status_user'], [4])) {
            $statusList = [];
            $buttonStatus = 0;
        } else {
            $buttonStatus = 0;
            $statusList = $status;
            unset($statusList[1],$statusList[2],$statusList[3],$statusList[4]);
        }

        $rs = [
            'status' => '200',
            'msg' => 'ok',
            'list' => $list,
            'recordInfo' => $this->recordInfo,
            'statusList' => $statusList,
            'buttonStatus' => $buttonStatus,
        ];

        return response()->json($rs);
    }

    public function status_list(Request $request, $rid = 0)
    {
        $record_id = $rid;
        $sh = [
            'record_id' => $record_id,
        ];
        $list = $this->jobRecordStatus->getList($sh, true);

        $recordInfo = $this->jobRecord->find($record_id);

        $jobInfo = $this->job->find($recordInfo['job_id']);

        $company = new Company();
        $companyInfo = $company->find($recordInfo['company_id']);


        //个人简历
        $experience = new Experience();
        $experienceInfo = $experience->where('user_id', $this->user->id)->first();

        return view('web.' . $this->viewName . '.status_list', ['list' => $list, 'jobInfo' => $jobInfo, 'recordInfo' => $recordInfo, 'experienceInfo' => $experienceInfo, 'companyInfo' => $companyInfo]);
    }

    public function memo_add(Request $request)
    {
        $data = $request->only(['record_id', 'status_user', "memo"]);
        $rs = [
            'status' => '200',
            'msg' => config('code.alert_msg.JobRecord.memo_save_success'),
        ];

        if (!$data['record_id'] || !$data['status_user'] || !$data['memo']) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
            return response()->json($rs);
        }

        $insertMemo = [
            'record_id' => $data['record_id'],
            'status_user' => $data['status_user'],
            'type' => 1,
            'memo' => $data['memo'],
        ];
        $memoId = $this->jobRecordMemo->saveItem($insertMemo);

        $date = date("Y-m-d H:i:s");
        $insertMemo = [
            'id' => $data['record_id'],
            'status_user' => $data['status_user'],
            'status_user_time' => $date,
        ];
        $id = $this->jobRecord->saveItem($insertMemo);

        if ($id === false || $memoId === false) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
        }

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
            'read_user' => 0,
            'read_user_time' => $date,
        ];
        $id = $this->jobRecord->saveItem($insertMemo);

        if ($id === false) {
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
        }

        return response()->json($rs);
    }

    public function status_add(Request $request)
    {
        $data = $request->only(['record_id', 'status_user', 'title', "content", "resume_1", "resume_2"]);
        $rs = [
            'status' => '200',
            'msg' => config('code.alert_msg.JobRecord.status_save_success'),
        ];

        $date = date("Y-m-d H:i:s");
        if (isset($data['status_user']) && in_array($data['status_user'], [3, 4])) {
            $data['title'] = config("code.record.status_user")[$data['status_user']];
            if ($data['status_user'] == 3) {
                $data['title'] = "開示しました。ご確認下さい。";
                $status_company = 4;

                $updateUser = [
                    'id' => $this->user->id,
                    'job_count' => DB::raw('job_count + 1'),
                ];

                $userObj = new User();
                $userObj->saveItem($updateUser);

            } else {
                $status_company = 5;
                $data['title'] = "書類開示検討中";
            }

            //插入pdf
            if ($data['resume_1'] && $data['resume_2']) {
                $experience = new Experience();
                $update = [
                    'resume_1' => $data['resume_1'],
                    'resume_2' => $data['resume_2'],
                ];

                $eid = $experience->where('user_id', $this->user->id)->update($update);
            }

            $updateRecord = [
                'id' => $data['record_id'],
                'status_user' => $data['status_user'],
                'status_user_time' => $date,
                'status_company' => $status_company,
                'status_company_time' => $date,
                //'read_user' => 0,
                //'read_user_time' => $date,
                'read_company' => DB::raw('read_company + 1'),
            ];

            if ($data['status_user'] == 4) {
                //$updateRecord['finish_status'] = 1;
            }

            $insertMemo = [
                'record_id' => $data['record_id'],
                'status_user' => $data['status_user'],
                'type' => 1,
                'memo' => $data['title'],
            ];
            $memoId = $this->jobRecordMemo->saveItem($insertMemo);

        } else {
            $updateRecord = [
                'id' => $data['record_id'],
                //'read_user' => 0,
                //'read_user_time' => $date,
                'read_company' => DB::raw('read_company + 1'),
            ];
        }

        $id = $this->jobRecord->saveItem($updateRecord);

        $insertStatus = [
            'record_id' => $data['record_id'],
            'status_user' => $data['status_user'],
            'type' => 1,
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

}