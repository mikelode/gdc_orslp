<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    protected $table = 'tramDocAnexos';
    protected $primaryKey = 'tdaId';
    public $timestamps = false;
}
