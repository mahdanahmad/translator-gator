<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class TranslatedWord extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'translated_words';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
    protected $fillable     = array('origin_word_id', 'translated_to', 'language_id', 'user_id',  'categorized_counter', 'counter_voteup', 'counter_votedown', 'alternate_source');

    // Relationships
	public function user() { return $this->belongsTo('App\User'); }
	public function language() { return $this->belongsTo('App\Language'); }
    public function origin_word() { return $this->belongsTo('App\OriginWord'); }
}
