<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Http\Controllers\Traits\BaseModelTrait;
use Illuminate\Support\Facades\Hash;

class Contact extends BaseModel
{

    protected $table = 'contact';
    protected $primaryKey = 'contact_id';
    protected $isDeleted = true;

}
