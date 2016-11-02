<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Language extends Eloquent {
	use SoftDeletes;

	protected $collection 	= 'languages';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
	protected $fillable		= array('language_name');

	// Relationships
	public function users() { return $this->belongsToMany('App\Models\User','speaks','language_id','user_id'); }
	public function translated_words() { return $this->hasMany('App\Models\TranslatedWord'); }
}
