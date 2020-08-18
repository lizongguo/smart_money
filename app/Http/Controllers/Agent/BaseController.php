<?php
namespace App\Http\Controllers\Agent;


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
            $this->user = Auth::guard('agent')->user();
            view()->share('userInfo', $this->user);

            $menu = [
                'resume_input' => [
                    'name' => '求職者簡単履歴登録',
                    'url' => route('agent.resume.input'),
                    'class' => "fa fa-users",
                ],
                'resume' => [
                    'name' => '登録者一覧',
                    'url' => route('agent.resume.index'),
                    'class' => "fa fa-file-text-o",
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
