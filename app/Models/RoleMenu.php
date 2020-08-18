<?php

namespace App\Models;

class RoleMenu extends BaseModel
{
    protected $table = 'role_menu';
    protected $primaryKey = ['role_id', 'menu_id'];
    protected $isDeleted = false;
    
    /**
     * @var array
     */
    protected $fillable = ['role_id', 'menu_id'];
}
