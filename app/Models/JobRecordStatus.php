<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobRecordStatus extends BaseModel
{
    protected $table = 'job_record_status';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

}