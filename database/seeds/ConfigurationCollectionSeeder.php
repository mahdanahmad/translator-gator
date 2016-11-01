<?php

use Illuminate\Database\Seeder;

class ConfigurationCollectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
		DB::collection('configurations') ->delete();
		DB::collection('configurations') ->insert(array(
            'display_items_per_page'   => 1,        // The field is pretty explanatory. feel free to change the value.
            'display_options_per_page' => 2,
            'alternate_point'          => 3,
            'categorize_point'         => 7,
            'translate_point'          => 6,
            'vote_down_point'          => 8,
            'vote_up_point'            => 10,
            'voter_point'              => 8,
            'referral_point'           => 28,
            'max_health'               => 5,
            'kick_time'                => 120,
            'is_on_translate'          => true,
            'is_on_alternative'        => true,
            'is_on_vote'               => true,
            'is_on_categorize'         => true,
            'point_value'              => 3,
            'redeem_time'              => false,
    	));
    }
}
