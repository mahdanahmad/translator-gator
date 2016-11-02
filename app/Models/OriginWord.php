<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class OriginWord extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'origin_words';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
    protected $fillable     = array('origin_word','translated_counter','voted_counter','category_id');

    // Relationships
	public function category_items() { return $this->belongsTo('App\Models\CategoryItem'); }
    public function translated_words() { return $this->hasMany('App\Models\TranslatedWord'); }
}
