<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->command->info("1. seeding Language Collection ...");
        $this->call('LanguageCollectionSeeder');

        $this->command->info("2. seeding Category && Category Item Collection ...");
        $this->call('CategoryCollectionSeeder');

        $this->command->info("3. seeding User Collection ...");
        $this->call('UserCollectionSeeder');

        $this->command->info("4. seeding Collection Configuration (also rewards & punishment)...");
        $this->call('ConfigurationCollectionSeeder');

        $this->command->info("5. initialize all other Collections ...");
        $this->call('InitCollectionSeeder');
    }
}
