<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'tramProyecto';
    protected $primaryKey = 'tpyId';
    public $timestamps = false;
}
