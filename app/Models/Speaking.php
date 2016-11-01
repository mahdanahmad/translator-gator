<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Speaking extends Eloquent {
	use SoftDeletes;

	protected $collection 	= "speaks";
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
	protected $fillable		= array('user_id', 'language_id', 'translated_counter', 'categorized_counter', 'voted_counter');

	// Relationships
    public function user() { return $this->belongsTo('App\User'); }
    public function language() { return $this->belongsTo('App\Language'); }
}
