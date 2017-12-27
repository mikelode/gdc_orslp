<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'tramDocumento';
    protected  $primaryKey = 'tdocId';
    public $timestamps = false;

    public function historial()
    {
        return $this->hasOne('aidocs\Models\Historial','thisDoc','tdocId');
    }
}
