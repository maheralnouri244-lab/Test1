<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
        protected $guarded =[];
        protected $hidden = ['pivot'];

        public function blog()
    {
        return $this->belongsToMany(Blog::class,'post_categories');
    }
}
