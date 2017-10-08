<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'tramRoles';
    protected $primaryKey = 'trolId';
    public $timestamps = false;

    public function users()
    {
        return $this->belongsTo('aidocs\User','trolIdUser','tusId');
    }

    public function permissions()
    {
        return $this->belongsTo('aidocs\Models\Sistema','trolIdSyst','tsysId');
    }
}
