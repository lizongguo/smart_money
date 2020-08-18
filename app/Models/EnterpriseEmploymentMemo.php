<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class EnterpriseEmploymentMemo extends Model
{
    protected $table = 'enterprise_employment_memos';
    protected $isDeleted = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'enterprise_employment_record_id',// 求职记录id
        'memo',// memo记录
        'admin_id',// 操作管理员id
    ];

    /**
     * 获取 企業管理 （RA用） 记录情报
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function record()
    {
        return $this->belongsTo(EnterpriseEmploymentRecord::class, 'enterprise_employment_record_id', 'id');
    }

}
