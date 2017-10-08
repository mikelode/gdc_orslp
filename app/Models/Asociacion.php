<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Asociacion extends Model
{
    protected $table = 'tramAsociacion';
    protected $primaryKey = 'tasId';
    public $timestamps = false;
}
