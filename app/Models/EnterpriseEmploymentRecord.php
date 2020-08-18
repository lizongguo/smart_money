<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class EnterpriseEmploymentRecord extends Model
{

    protected $table = 'enterprise_employment_records';
    protected $isDeleted = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',// 企业id
        'resume_id',// 企业名称
        'status',// ステータス
        'admin_id',// 创建者id
        'updated_at', //更新时间
    ];

    const STATUS_TEXT = [
        0 => '',
        1 => '書類選考中',
        2 => '書類選考不合格',
        3 => '面接設定',
        4 => '面接済',
        5 => '内定',
        6 => '入社',
        7 => '辞退',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * 获取记录 memo list
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memos()
    {
        return $this->hasMany(EnterpriseEmploymentMemo::class, 'enterprise_employment_record_id', 'id')
            ->orderBy('id', 'desc');
    }

    /**
     * 获取最后memo记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function memo()
    {
        return $this->hasOne(EnterpriseEmploymentMemo::class, 'enterprise_employment_record_id', 'id')
            ->orderBy('id', 'desc');
    }

    /**
     * 获取公司情报
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')
            ->select('id', 'company_name', 'fileds', 'fileds_other');
    }

    /**
     * 获取求职者情报
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resume_id', 'resume_id');
    }

    /**
     * 获取求职者聊天记录情报
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employment_record()
    {
        return $this->belongsTo(EmploymentRecord::class, 'resume_id', 'resume_id');
    }

    /**
     * 获取求职者CA comments
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comments()
    {
        return $this->hasMany(EmploymentRecordComment::class, 'resume_id', 'resume_id')
            ->orderBy('id', 'desc');
    }

    /**
     * 获取求职者CA 最新一天记录 comment
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {
        return $this->hasOne(EmploymentRecordComment::class, 'resume_id', 'resume_id')
            ->orderBy('id', 'desc');
    }



}
