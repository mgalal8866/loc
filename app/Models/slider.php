<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class slider extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function getImageAttribute($val){
        $path = public_path('assets/images/slider/'. $val);
        if(File::exists($path)) {
             return ($val !== null ) ? asset('assets/images/slider/' . $val) : asset('assets/images/noimage.jpg');
        }else{
             return asset('assets/images/noimage.jpg');
        }
    }
    public function branch()
    {
        return $this->belongsTo(branchs::class,'event','id');
    }

    public function product()
    {
        return $this->belongsTo(products::class,'event','id');
    }
    public function region()
    {
        return $this->belongsTo(regions::class);
    }
}
