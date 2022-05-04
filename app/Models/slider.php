<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class slider extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getImageAttribute($val){
        return ($val !== null ) ? asset('assets/images/slider/' . $val) : asset('assets/images/noimage.jpg');
    }
}
