<?php

namespace App\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Category extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'categories';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
    protected $fillable     = array('category_group');

    // Relationships
    public function category_items() { return $this->hasMany('App\CategoryItem'); }
}
