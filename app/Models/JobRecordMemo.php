<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobRecordMemo extends BaseModel
{
    protected $table = 'job_record_memo';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

}