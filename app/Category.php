<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Category extends Model
{   
    
    public $timestamps = false;


    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produktu', 'id');
    }


}
