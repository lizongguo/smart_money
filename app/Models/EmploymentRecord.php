<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class EmploymentRecord extends Model
{

    protected $table = 'employment_records';
    protected $isDeleted = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'resume_id',// 简单履历id
        'job_ids',// 応募求人ids
        'admin_id',// 操作id
        'updated_at', //更新时间
    ];


    /**
     * 获取求职者CA comments
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function comments()
    {
        return $this->hasMany(EmploymentRecordComment::class, 'employment_record_id', 'id')
            ->orderBy('id', 'desc');
    }

    /**
     * 获取求职者CA 最新一条 comment
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function comment()
    {
        return $this->hasOne(EmploymentRecordComment::class, 'employment_record_id', 'id')
            ->orderBy('id', 'desc');
    }

    /**
     * 获取求职者情报
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resume_id', 'resume_id');
    }

    public function getJobIdsAttribute($value)
    {
        if (!empty($value)) {
            return explode(',', $value);
        } else {
            return [];
        }
    }

    public function setJobIdsAttribute($value)
    {
        if (!empty($value) && is_array($value) && count($value) > 0) {
            $setter = implode(',', $value);
        } else {
            $setter = '';
        }
        $this->attributes['job_ids'] = $setter;
    }

    public function companies() {
        return $this->belongsToMany(Company::class, 'employment_record_companies', 'employment_record_id', 'company_id')->select(['company.id','company.company_name', 'email', 'address_id', 'address']);
    }



}
