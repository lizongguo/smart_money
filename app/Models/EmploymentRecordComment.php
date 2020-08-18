<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class EmploymentRecordComment extends Model
{
    protected $table = 'employment_record_comments';
    protected $isDeleted = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'employment_record_id',// 求職者管理记录id
        'resume_id',// 用户简单履历id
        'content',// コメント
        'admin_id',// 管理者id
    ];

    /**
     * 获取 求職者管理（CA用） 记录情报
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function record()
    {
        return $this->belongsTo(EmploymentRecord::class, 'employment_record_id', 'id');
    }

}
