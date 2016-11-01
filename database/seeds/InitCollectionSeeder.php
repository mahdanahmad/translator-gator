<?php

use Illuminate\Database\Seeder;
use Jenssegers\Eloquent\Model;

class InitCollectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::collection('log')->delete();
    	DB::collection('redeem')->delete();
    	DB::collection('speaks')->delete();
        DB::collection('origin_words')->delete();
    	DB::collection('translated_words')->delete();
        DB::collection('categorized_words')->delete();
    }
}
