<?php

namespace App\Models;
use App\Models\BaseModel as Model;

class EmploymentRecordCompany extends Model
{
    protected $table = 'employment_record_companies';

    protected $fillable = [
        'employment_record_id',// 求職者管理记录id
        'company_id',// 公司id
    ];

}
