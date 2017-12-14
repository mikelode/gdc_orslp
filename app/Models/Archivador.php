<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Archivador extends Model
{
    protected $table = 'tramArchivador';
    protected $primaryKey = 'tarcId';
    public $timestamps = false;

    public function document()
    {
        return $this->hasOne('aidocs\Models\Document','tdocId','tarcDoc');
    }
}
