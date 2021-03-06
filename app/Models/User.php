<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class User extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'users';
    protected $hidden       = array('created_at', 'updated_at', 'password');
    protected $dates        = array('deleted_at');
    protected $fillable     = array('username', 'password', 'email', 'facebook_id', 'twitter_id', 'role', 'languages', 'point', 'isconfirmed', 'confirmationcode', 'resetcode', 'gender', 'age_range', 'isVirgin', 'referral', 'last_kicked', 'health');

    // Relationships
    public function translated_words() { return $this->hasMany('App\Models\TranslatedWord'); }

    // Helper function
    public function getAuthPassword() { return $this->password; }
}
