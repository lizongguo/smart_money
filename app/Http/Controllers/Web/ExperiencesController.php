<?php
/**
 * Created by Netbeans.
 * User: yutlong
 * Date: 2019/03/01
 * Time: 15:01
 */

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Experiences\StoreVideoRequest;
use App\Jobs\WebmToMp4Job;
use Illuminate\Http\Request;
use App\Http\Requests\Web\Experiences\StoreRequest;
use App\Models\User;
use App\Models\Experience;
use Validator;

class ExperiencesController extends BaseController
{
    public function __construct(Experience $model, User $users) {
        parent::__construct();
    }

    /**
     * 履歴書・職務経歴書
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function my(Request $request)
    {
        $menu_active = "experiences";
        view()->share('menu_active', $menu_active);

        $user = $this->user;
        $experience = Experience::getExperienceByUserId($user->id);

        $experienceArr = null;
        if ($experience) {
            $experienceArr = $experience->toArray();
            Experience::array2string($experienceArr);
        }
        return view('web.' . $this->viewName . '.my', ['experience' => $experienceArr ,'isMyPage' => 1]);
    }

    /**
     * 保存数据
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $user = $this->user;
        $experience = Experience::getExperienceByUserId($user->id);
        $data = $request->all();

        if ($data['is_qualification_and_license'] < 1) {
            $data['qualification_and_license'] = [];
        }
        if ($data['is_experience'] < 1) {
            $data['experiences'] = [];
        }
        if (!isset($data['it_skill_office'])) {
            $data['it_skill_office'] = [];
        }

        if (!isset($data['it_skill_os'])) {
            $data['it_skill_os'] = [];
        }

        if ($experience) {
            $rs = $experience->update($data);
        } else {
            $experience = Experience::create($data);
            $rs = $experience->id ?  true : false;
        }
        if(!$rs) {
            return response()->json([
                'status' => 500,
                'msg' => config('code.alert_msg.experiences.save_failed'),
            ]);
        }

        return $this->dataToJson([
            'status' => 200,
            'msg' => config('code.alert_msg.experiences.save_success'),
            'data' => $experience,
        ]);
    }


    /**
     * 上传视频页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadVideo(Request $request)
    {
        $user = $this->user;
        $experience = Experience::getExperienceByUserId($user->id);
        $upload = $request->input('upload', 0);

        return view('web.' . $this->viewName . '.uploadVideo', ['experience' => $experience, 'upload' => $upload]);

    }

    /**
     * 上传视频页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeVideo(StoreVideoRequest $request)
    {
        $user = $this->user;
        $experience = Experience::getExperienceByUserId($user->id);

        $data = $request->all();
        if ($experience) {
            $rs = $experience->update($data);
        } else {
            $experience = new Experience();
            $data['user_id'] = $user->id;
            $rs = $experience->saveItem($data);
        }

        if(!$rs) {
            return response()->json([
                'status' => 500,
                'msg' => config('code.alert_msg.experiences.video_save_failed'),
            ]);
        }
        //如果上传是webm格式的视频，通过ffmp转码视频 异步队列
        if (preg_match("/\.webm$/i", $data['video_url'])) {
            $this->dispatch(new WebmToMp4Job($experience->id));
        }

        return $this->dataToJson([
            'status' => 200,
            'msg' => config('code.alert_msg.experiences.video_save_success'),
            'data' => $experience,
        ]);
    }

    public function download(Request $request)
    {
        set_time_limit(0);
        ini_set("max_execution_time", 0);
        $user = $this->user;
        $experience = Experience::getExperienceByUserId($user->id);
        if (!$experience['photo']) {
            return view('web.' . $this->viewName . '.download', ['msg' => config('code.alert_msg.experiences.download_null')]);
        }
        $experience = Experience::dealItemData($experience);
        $pdfUrl = $experience->downloadPdf();

        if (!$pdfUrl) {
            return view('web.' . $this->viewName . '.download', ['msg' => config('code.alert_msg.experiences.download_error')]);
        }
        $filename = public_path() . $pdfUrl;
        $pathinfo = pathinfo($filename);
        header('Content-type: application/pdf');
        header('Content-Disposition: download; filename='.$pathinfo['basename']);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        readfile($filename);
        exit();
    }



}