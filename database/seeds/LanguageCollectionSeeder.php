<?php

use Illuminate\Database\Seeder;
use Jenssegers\Eloquent\Model;

class LanguageCollectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Example list, change this array to any languages that will be used
        $list   = array('indonesia', 'jawa', 'sunda', 'minang', 'bugis', 'melayu');

    	DB::collection('languages')->delete();
        DB::collection('languages')->insert(array_map(function($o) { return array('language_name' => $o); }, $list));
    }
}
