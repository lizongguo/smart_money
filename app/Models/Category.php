<?php

namespace App\Models;

use App\Http\Controllers\Traits\ModelTree;

class Category extends BaseModel
{
    use ModelTree;
    
    protected $table = 'category';
    protected $primaryKey = 'id';
    
    protected $fillable = ['name', 'parent_id', 'order', 'state'];
    
}
