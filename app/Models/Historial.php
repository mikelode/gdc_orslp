<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'tramHistorial';
    protected $primaryKey = 'thisId';
    public $timestamps = false;

    public function expedient()
    {
        return $this->belongsTo('aidocs\Models\Archivador','thisExp','tarcExp');
    }
}
