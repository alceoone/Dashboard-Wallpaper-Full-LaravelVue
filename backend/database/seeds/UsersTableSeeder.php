<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'alceo',
            'id_icons' => 1,
            'email' => 'alceo@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
            
        ]);
        DB::table('settings')->insert([
            'id' => 1,
            'url' => 'http://localhost:8000/'
        ]);
        DB::table('icons')->insert([
            'id_icons' => 1,
            'folder' => 'user',
            'name' => 'default',
            'extension' => 'png',
            'url' => 'http://localhost:8000/user/default.png'
        ]);
    }
}
