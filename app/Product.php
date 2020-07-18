<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{   
    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
