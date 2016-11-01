<?php

use Illuminate\Database\Seeder;

class UserCollectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Example list, this seed will only create admin role, if you wanna create user account, please use normal register.
        // The pattern for each list value will be array[0] for username, array[1] for password (it will be hashed later, calma) and array[2] for email.
        // Add as many as you like.
        $list   = array(
            array('pulselab', 'nopassword', 'plj@un.or.id'),
        );

        DB::collection('users')->delete();
        DB::collection('users')->insert(array_map(function($o) {
            return array(
                'username'        => $o[0],
                'password'        => app('hash')->make($o[1]),
                'email'           => $o[2],
                'facebook_id'     => '',
                'twitter_id'      => '',
                'point'           => 0,
                'role'            => 'admin',
                'word_translated' => 0,
                'word_categorized'=> 0,
                'languages'		  => '',
                'isconfirmed'	  => true,
                'resetcode'		  => '',
                'isVirgin'        => 0,
            );
        }, $list));
    }
}
