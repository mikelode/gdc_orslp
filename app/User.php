<?php

namespace aidocs;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = 'users';
    protected $table = 'tramUsuario';
    protected $primaryKey = 'tusId';
    public $timestamps = false;
    public $remember_token = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tusNickName', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

/*    public function profile()
    {
        return $this->hasOne('aidocs\UserProfile');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }*/

    public function workplace()
    {
        return $this->hasOne('aidocs\Models\Dependencia','depId','tusWorkDep');
    }

    public function can($permission = null)
    {
        return !is_null($permission) && $this->checkPermission($permission);
    }

    protected function checkPermission($perm)
    {
        $permissions = $this->getAllPermissionsFromAllRoles();
        $permissionArray = is_array($perm) ? $perm : [$perm];

        //dd($permissions);
        //dd($permissionArray);

        return count(array_intersect($permissions, $permissionArray));
    }

    protected function getAllPermissionsFromAllRoles()
    {
        $permissions = $this->roles->load('permissions')->toArray();

        //dd($permissions);

        $permissionArray = array_unique(array_flatten(array_map(function($permission){
            if($permission['trolEnable'])
            {
                return array_fetch($permission, 'tsysId');
            }
            return null;
        }, $permissions)));

        //dd($permissionArray);

        return array_map('strtolower', $permissionArray);
    }

    public function roles()
    {
        return $this->hasMany('aidocs\Models\Rol','trolIdUser','tusId');
    }
}
