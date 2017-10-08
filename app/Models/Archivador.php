<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Archivador extends Model
{
    protected $table = 'tramArchivador';
    protected $primaryKey = 'tarcExp';
    public $timestamps = false;

    public function document()
    {
        return $this->hasOne('aidocs\Models\Document','tdocId','tarcDoc');
    }
}
