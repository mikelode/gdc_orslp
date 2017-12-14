<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    protected $table = 'tramDependencia';
    protected $primaryKey = 'depId';
    public $timestamps = false;

    public  function getNameDep()
    {
        return $this->depDsc;
    }
}
