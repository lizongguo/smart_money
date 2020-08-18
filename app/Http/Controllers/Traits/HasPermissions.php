<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Collection;

trait HasPermissions
{
    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public function allPermissions() : Collection
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->merge($this->permissions);
    }

    /**
     * Check if user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function canPermission($permission) : bool
    {
        if ($this->isAdministrator()) {
            return true;
        }
        
        if ($this->permissions->pluck('id')->contains($permission)) {
            return true;
        }
        return $this->roles->permissions->flatten()->pluck('id')->contains($permission);
    }

    /**
     * Check if user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannotPermission($permission) : bool
    {
        return !$this->canPermission($permission);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator() : bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole(string $role) : bool
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * Check if user in $roles.
     *
     * @param array $roles
     *
     * @return mixed
     */
    public function inRoles($roles = []) : bool
    {
        return $this->roles->pluck('slug')->intersect($roles)->isNotEmpty();
    }

    /**
     * If visible for roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function visible($roles = []) : bool
    {
        if (empty($roles) || count($roles) < 1) {
            return true;
        }
        $roles = $roles->pluck('slug')->toArray(); //array_column(, 'slug');
        
        return $this->inRoles($roles) || $this->isAdministrator();
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->roles()->detach();

            $model->permissions()->detach();
        });
    }
}
