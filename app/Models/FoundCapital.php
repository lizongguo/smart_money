<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;



class FoundCapital extends BaseModel
{
    protected $table = 'found_capital';
    protected $primaryKey = 'id';

    public function getOne($id)
    {
        $found = $this->select('*')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();
        if (!$found) {
            return false;
        }
        return $found;
    }

    public function saveFinancial($data)
    {
        if ($data['id']) {
            $id = $data['id'];
            $update = [
                'fund_id' => $data['fund_id'],
                'user_id' => (int)$data['user_id'],
                'type' => $data['type'],
                'amount_cn' => $data['amount_cn'],
                'amount_us' => $data['amount_us'],
                'paid_date' => $data['paid_date'],
                'name' => $data['name'],
            ];
            return $this->where('id', $id)->update($update);
        } else {
            $insert = [
                'fund_id' => $data['fund_id'],
                'user_id' => (int)$data['user_id'],
                'name' => $data['name'],
                'type' => $data['type'],
                'amount_cn' => $data['amount_cn'],
                'amount_us' => $data['amount_us'],
                'paid_date' => $data['paid_date'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->insert($insert);
        }
    }
}
