<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayOrders extends BaseModel
{
    protected $table = 'pay_orders';
    protected $primaryKey = 'id';
    protected $isDeleted = false;


    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function order() : BelongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
    
    /**
     * 获取支付订单，通过支付no
     * @param type $order_no
     * @return type
     */
    public function getDataByOrderNo($order_no)
    {
        $data = $this->where('pay_order_no', $order_no)->first();
        if (!!$data) { 
            $data->order;
        }
        return $data;
    }
    
    /**
     * 获取支付订单，通过订单id
     * @param type $order_id
     * @return type
     */
    public function getDataByOrderId($order_id)
    {
        $data = $this->where('order_id', $order_id)->where('state', '1')->first();
        return $data;
    }
    
    /**
     * 创建支付订单
     * @param type $order_id
     * @param type $user
     * @param type $pay_method
     * @param type $total_amount
     * @param type $preferential_amount
     * @param type $payment_amount
     * @return boolean
     */
    public function createPayOrder($order_id, $user, $pay_method, $total_amount, $preferential_amount, $payment_amount)
    {
        //生成支付订单
        $data = [
            'pay_order_no' => md5(time() . $this->getRandomStr(10)),
            'order_id' => $order_id,
            'user_id' => $user['id'],
            'username' => $user['username'],
            'pay_method' => $pay_method,
            'total_amount' => $total_amount,
            'preferential_amount' => $preferential_amount,
            'payment_amount' => $payment_amount,
            'state' => 0,
            'trade_no' => '',
            'paymentdata' => ''
        ];
        $id = $this->saveItem($data);
        if (!$id) {
            return false;
        }
        $data['id'] = $id;
        return $data;
    }
    
}
