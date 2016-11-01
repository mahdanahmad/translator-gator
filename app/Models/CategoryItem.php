<?php

namespace App\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class CategoryItem extends Eloquent {
	use SoftDeletes;

	protected $collection 	= 'category_items';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
	protected $fillable		= array('category_name', 'description', 'icon_selected', 'icon_unselected', 'category_id');

	// Relationships
    public function category() { return $this->belongsTo('App\Category'); }
}
