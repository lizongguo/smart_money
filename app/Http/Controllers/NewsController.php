<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobObj;

class NewsController extends Controller
{
    /**
     *
     * @var App\Models\News 
     */
    public $model = null;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(News $news)
    {
        $this->model = $news;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, $id)
    {
        $item = $this->model->where('state', '1')->where('deleted', 0)->where('id', intval($id))->first();
        if (!!$item) {
            $item->hits = $item->hits + 1;
            $item->save();
        }
        return view('web.news.detail', ['item' => $item]);
    }
}
