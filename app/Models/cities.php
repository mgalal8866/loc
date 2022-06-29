<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cities extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];



    Public function getNameAttribute()
    {
        $city_name = 'city_name_'.config('err_message.config.lang_for_felid');
        return $this->$city_name;
    }

    public function branch()
    {
        return $this->hasMany(branchs::class,'city_id');
    }

    public function region()
    {
        return $this->hasMany(regions::class,'city_id');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
