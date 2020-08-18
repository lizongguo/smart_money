<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Log;

class Permissions extends BaseModel
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $isDeleted = false;
    
    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'http_method', 'http_path'];

    /**
     * @var array
     */
    public static $httpMethods = [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD',
    ];
    
    /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Roles::class, 'role_permissions', 'permission_id', 'role_id');
    }
    
    /**
     * If request should pass through the current permission.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function shouldPassThrough(Request $request) : bool
    {
        if (empty($this->http_method) && empty($this->http_path)) {
            return true;
        }

        $method = $this->http_method;
        
        $matches = array_map(function ($path) use ($method) {
            
            if (Str::contains($path, ':')) {
                list($method, $path) = explode(':', $path);
                $method = explode(',', $method);
            }

            return compact('method', 'path');
        }, preg_split("/[\r\n]+/", $this->http_path, -1, 1));
        
        
        foreach ($matches as $match) {
            if ($this->matchRequest($match, $request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * If a request match the specific HTTP method and path.
     *
     * @param array   $match
     * @param Request $request
     *
     * @return bool
     */
    protected function matchRequest(array $match, Request $request) : bool
    {
        if (!$request->is(trim($match['path'], '/'))) {
            return false;
        }

        $method = collect($match['method'])->filter()->map(function ($method) {
            return strtoupper($method);
        });

        return $method->isEmpty() || $method->contains($request->method());
    }
    
    /**
     * @param $method
     */
    public function setHttpMethodAttribute($method)
    {
        if (is_array($method)) {
            $this->attributes['http_method'] = implode(',', $method);
        }
    }

    /**
     * @param $method
     *
     * @return array
     */
    public function getHttpMethodAttribute($method)
    {
        if (is_string($method)) {
            return array_filter(explode(',', $method));
        }

        return $method;
    }
}
