<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    protected $table = 'TLogGrlDep';
    protected $primaryKey = 'depID';
    public $timestamps = false;

    public  function getNameDep()
    {
        return $this->depDsc;
    }
}
