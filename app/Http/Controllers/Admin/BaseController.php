<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Controllers\Traits\AdminManagerTrait;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    use AdminManagerTrait;
    
    protected $viewName;
    protected $pageCount = 20;

    protected $model;
    protected $sh;
    protected $user;
    protected $pageTitle;
    
    public function __construct() {
        $this->setViewName();
        
        //设置user变量
        $this->middleware(function($request, $next) {
            $this->user = Auth::guard('admin')->user();
            view()->share('user', $this->user);
            
            //错误提示语
            $errorSession = session()->get('errorSession');
            if (!!$errorSession) {
                view()->share('errorSession', $errorSession);
            }
            return $next($request);
        });
        
        //推送code变量到前台
        view()->share('code', config('code'));
    }
    
    public function setViewName() {
        $this->viewName = strtolower(preg_replace('#^(.*)\\\([^\\\]+)Controller$#is', '$2', get_class($this)));
    }
    
    /**
     * @return mixed
     * check has  login
     */
    public function hasLogin(){
        return Auth::guard('admin')->check();
    }
}
