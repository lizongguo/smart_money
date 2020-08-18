<?php
namespace App\Services\Dada\Sdk\model;
/**
 * http://newopen.imdada.cn/#/development/file/add?_k=5f4vjj
 *
 */
class QueryModel{
    
    
    public $order_id;

    public function setOrderId($order_id)
    {
        !empty($order_id) ? $this->order_id = $originId : trigger_error('order_id不能为空', E_USER_ERROR);
    }

    public function getOrderId()
    {
        return $this->order_id;
    }

}
