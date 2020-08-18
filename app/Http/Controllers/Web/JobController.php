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

class JobController extends BaseController
{
    public function __construct(Job $model) {
        parent::__construct();
        $this->model = $model;

        view()->share('pageTitle', $this->pageTitle);
    }

    public function index(Request $request)
    {
        $list = $this->model->getList($this->sh, false, $this->pageCount);

        return view('web.' . $this->viewName . '.index', ['list' => $list]);
    }

}