<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Popular extends BaseModel
{
    protected $table = 'job_popular';
    protected $primaryKey = 'id';
    protected $isDeleted = false;

}