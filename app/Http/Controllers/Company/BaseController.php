<?php
namespace App\Http\Controllers\Company;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected $viewName;
    protected $controllerName;
    protected $pageCount = 10;

    protected $model;
    protected $sh;
    protected $user;
    protected $pageTitle;
    
    public function __construct() {
        $this->setViewName();
        $this->controllerName();

        $this->middleware(function($request, $next) {
            $this->user = Auth::guard('company')->user();
            view()->share('userInfo', $this->user);

            $menu = [
                'job' => [
                    'name' => '求人管理',
                    'url' => route('company.job.index'),
                    'class' => "fa fa-calendar-check-o",
                ],
                'record' => [
                    'name' => '応募管理',
                    'url' => route('company.record.index', ['record']),
                    'class' => "fa fa-id-card-o",
                ],
                'scout' => [
                    'name' => 'スカウト管理',
                    'url' => route('company.record.index', ['scout']),
                    'class' => "fa fa-id-card-o",
                ],
            ];
            view()->share('web_user_menu', $menu);

            return $next($request);
        });
    }
    
    public function setViewName() {
        $this->viewName = strtolower(preg_replace('#^(.*)\\\([^\\\]+)Controller$#is', '$2', get_class($this)));
    }

    public function controllerName() {
        $this->controllerName = preg_replace('#^(.*)\\\([^\\\]+)Controller$#is', '$2', get_class($this));
    }

    protected function dataToJson($data, $httpd_code = '200')
    {
        return response()->json($data, $httpd_code);
    }
}
