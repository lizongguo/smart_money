<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Companys extends Authenticatable
{
    protected $table = 'company';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

}
