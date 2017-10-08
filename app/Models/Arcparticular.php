<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Arcparticular extends Model
{
    protected $table = 'tramArchivadorParticular';
    protected $primaryKey = 'tarpId';
    public $timestamps = false;

    public function arcpdocument()
    {
        return $this->hasOne('aidocs\Models\Document','tdocId','tarpDoc');
    }
}