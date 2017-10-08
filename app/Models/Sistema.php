<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    protected $table = 'tramSistema';
    protected $primaryKey = 'tsysId';
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany('aidocs\Models\Rol','trolIdSyst','tsysId');
    }
}
