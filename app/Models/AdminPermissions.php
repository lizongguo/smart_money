<?php

namespace App\Models;

use Log;

class AdminPermissions extends BaseModel
{
    protected $table = 'admin_permissions';
    protected $primaryKey = ['admin_id', 'permission_id'];
    protected $isDeleted = false;
    
    /**
     * @var array
     */
    protected $fillable = ['admin_id', 'permission_id'];
}
