<?php
class UserSeeder extends Seeder {
    public function run() {
        DB::table('users')->insert([
            'id' => 1,
            'username' => 'user1',
            'name' => 'Chuey Savedra',
            'password' => Hash::make('qwerty'),
            'email' => 'user1@example.com',
        ]);
    }
}
