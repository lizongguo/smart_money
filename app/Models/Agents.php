<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agents extends Authenticatable
{
    protected $table = 'agent';
    protected $primaryKey = 'id';
    protected $isDeleted = true;

}
