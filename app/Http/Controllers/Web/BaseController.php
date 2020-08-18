<?php
namespace App\Http\Controllers\Web;


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
            $this->user = Auth::guard('web')->user();
            view()->share('userInfo', $this->user);
            view()->share('thisControllerName', $this->controllerName);

            $menu = [
                'record' => [
                    'name' => '応募履歴',
                    'url' => route('web.record.index', ['record']),
                    'class' => "fa fa-id-card-o",
                ],
                'scout' => [
                    'name' => 'スカウト履歴',
                    'url' => route('web.record.index', ['scout']),
                    'class' => "fa fa-calendar-check-o",
                ],
                'experiences' => [
                    'name' => '履歴書・職務経歴書',
                    'url' => route("web.experiences.my"),
                    'class' => "fa fa-file-text-o",
                ],
                'favorite' => [
                    'name' => 'お気に入り',
                    'url' => route("web.favorite.index"),
                    'class' => "fa fa-heart-o",
                ],
            ];
            view()->share('web_user_menu', $menu);

            $needLogin = ["User", "Favorite", 'Experiences', 'JobRecord'];
            if (in_array($this->controllerName, $needLogin)) {
                if (!$this->user) {
                    return redirect()->route('web.index.login');
                }
            }

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
