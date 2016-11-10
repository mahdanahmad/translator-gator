<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Log extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'logs';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
    protected $fillable     = array('action_type', 'user_id', 'raw_result', 'result', 'affected_user', 'translated_id', 'origin_id', 'category_items');

    // Relationships
	public function user() { return $this->belongsTo('App\Models\User'); }
}
