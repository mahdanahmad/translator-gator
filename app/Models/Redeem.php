<?php

namespace App\Models;

use DB;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Redeem extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'redeem';
    protected $hidden       = array('created_at', 'updated_at', 'user_id');
    protected $dates        = array('deleted_at');
	protected $fillable     = array('_id','mobile','credit','status', 'user_id', 'points', 'prev');

    // Relationships
	public function user() { return $this->hasOne('App\Models\User', '_id', 'user_id'); }

    // Helper function to make _id in sequence
    public static function getNextSequence() {
        $result = DB::getCollection('counters')->findAndModify(
            array('_id' => 'transaction_id'),
            array('$inc' => array('seq' => 1)),
            null,
            array('new' => true, 'upsert' => true)
       );

        return $result['seq'];
    }
}
