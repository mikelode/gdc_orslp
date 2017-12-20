<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'tramPersona';
    protected $primaryKey = 'tprId';
    public $timestamps = false;
}
