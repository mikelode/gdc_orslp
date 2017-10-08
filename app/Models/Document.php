<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'tramDocumento';
    protected  $primaryKey = 'tdocId';
    public $timestamps = false;

    public function todo()
    {
        return $this->hasManyThrough('aidocs\Models\Historial','aidocs\Models\Archivador','tarcDoc','thisExp');
    }
}
