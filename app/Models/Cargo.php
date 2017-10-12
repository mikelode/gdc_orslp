<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'tramCargo';
    protected $primaryKey = 'tcgId';
    public $timestamps = false;
}
