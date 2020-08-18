<?php
namespace App\Services\Dada\Sdk\model;
/**
 * http://newopen.imdada.cn/#/development/file/add?_k=5f4vjj
 *
 */
class CancelModel{
    
    
    public $order_id;

    public $cancel_reason_id;

    public $cancel_reason;

    public function setOrderId($order_id)
    {
        !empty($order_id) ? $this->order_id = $originId : trigger_error('order_id不能为空', E_USER_ERROR);
    }

    public function getOrderId()
    {
        return $this->order_id;
    }
    
    public function setCancelReasonId($cancel_reason_id)
    {
        !empty($cancel_reason_id) ? $this->cancel_reason_id = $cancel_reason_id : trigger_error('cancel_reason_id不能为空', E_USER_ERROR);
    }

    public function getCancelReasonId()
    {
        return $this->cancel_reason_id;
    }

    public function setCancelReason($cancel_reason)
    {
        isset($cancel_reason) ? $this->cancel_reason = $cancel_reason : '';
    }

    public function GetCancelReason()
    {
        return $this->cancel_reason;
    }


}
