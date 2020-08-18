<?php
/**
 * upload Controller
 *
 * @package       Api.Controller
 * @author        lee
 * @since         PHP 7.0.1
 * @version       1.0.0
 * @copyright     Copyright(C) bravesoft Inc.
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Services\Dada\DadaPeisong;
class TestController extends BaseController
{
    protected $dada = null;

    public function __construct(Request $request, DadaPeisong $dada)
    {
        parent::__construct($request);
        
        $this->dada = $dada;
    }
    
    /**
     *  test push
     * @param Request $request
     * @param type $type
     * @param type $aid
     * @return type
     */
    public function index(Request $request)
    {
        $order_id = $request->input('order_id', 0);
        $status = $request->input('status', 2);
        
        $result = $this->dada->test($order_id, $status);
        $this->back['data'] = $result;
        return $this->back;
    }
    
    
}
