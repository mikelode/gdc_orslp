<?php

namespace aidocs;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    public function getAgeAttribute()
    {
        return Carbon::parse($this->birthdate)->age;
    }
}
