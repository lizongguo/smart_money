<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class FindPassword extends BaseModel
{
    protected $table = 'find_password';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

}
