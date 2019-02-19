<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class makeUserAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Your Name',
            'tempat_lahir' => 'Place of Birth',
            'tgl_lahir' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
            'address' => 'Your Address',
            'email' => 'your@email.com',
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'img' => null,
        ]);
    }
}
