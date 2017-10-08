<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tramTipoDocumento';
    protected $primaryKey = 'ttypDoc';
    public $timestamps = false;
}
