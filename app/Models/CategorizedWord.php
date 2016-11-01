<?php

namespace App\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class CategorizedWord extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'categorized_words';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
    protected $fillable     = array('translated_word_id', 'categorized_to', 'user_id');

    // Relationships
    public function translated_word() { return $this->hasOne('App\TranslatedWord', '_id', 'translated_word_id'); }
}
