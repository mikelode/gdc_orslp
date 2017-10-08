<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerDep extends Model
{
    protected $table = 'tramRepresentanteDep';
    protected  $primaryKey = 'trepId';
    public $timestamps = false;
}
