<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Configuration extends Eloquent {
    use SoftDeletes;

    protected $collection   = 'configurations';
    protected $hidden       = array('created_at', 'updated_at');
    protected $dates        = array('deleted_at');
    protected $fillable     = array('display_items_per_page', 'display_options_per_page', 'alternate_point', 'categorize_point', 'vote_down_point', 'vote_up_point', 'voter_point', 'referral_point', 'max_health', 'kick_time', 'is_on_translate', 'is_on_alternative', 'is_on_vote', 'is_on_categorize', 'point_value', 'redeem_time');
}
