<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Job;
use Illuminate\Support\Facades\Mail;
use Validator;
use DB;

class FavoriteController extends BaseController
{
    public function __construct(Favorite $model, User $users, Job $job) {
        parent::__construct();
        $this->model = $model;
        $this->users = $users;
        $this->job = $job;
    }

    function favorite(Request $request) {
        $data = $request->only(['user_id', 'job_id']);

        $rs = [
            'status' => '200',
            'msg' => '操作が成功しました。',
        ];
        $info = $this->model->where("user_id", $data['user_id'])->where("job_id", $data['job_id'])->first();

        DB::beginTransaction();
        if ($info) {
            $id = $info->delete();
            $updateJob = [
                'job_id' => $data['job_id'],
                'fav_count' => DB::raw('fav_count - 1'),
            ];

            $rs['msg'] = config('code.alert_msg.favorite.favorite_cancel_success');
        } else {
            $insert = [
                'user_id' => $data['user_id'],
                'job_id' => $data['job_id'],
            ];

            $id = $this->model->saveItem($insert);

            $updateJob = [
                'job_id' => $data['job_id'],
                'fav_count' => DB::raw('fav_count + 1'),
            ];

            $rs['msg'] = config('code.alert_msg.favorite.favorite_success');
        }

        $updateId =  $this->job->saveItem($updateJob);
        if ($id === false || $updateId === false) {
            DB::rollback();
            $rs['status'] = '500';
            $rs['msg'] = config('code.alert_msg.system.error');
            return response()->json($rs);
        }

        DB::commit();

        return response()->json($rs);
    }

    public function index(Request $request)
    {
        $menu_active = "favorite";
        view()->share('menu_active', $menu_active);

        $sh = [
            'favorite.user_id' => $this->user->id,
        ];
        $list = $this->model->getList($sh, false, $this->pageCount, ['jobs.job_id', 'jobs.position']);

        return view('web.' . $this->viewName . '.index', ['list' => $list]);
    }

}