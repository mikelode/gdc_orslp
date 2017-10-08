<?php

namespace aidocs\Models;

use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    protected $table = 'tramReleases';
    protected  $primaryKey = 'treId';
    public $timestamps = false;
}
