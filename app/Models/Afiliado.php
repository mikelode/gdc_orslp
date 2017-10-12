<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Afiliado extends Model
{
    protected $table = 'tramAfiliados';
    protected $primaryKey = 'tafId';
    public $timestamps = false;
}
