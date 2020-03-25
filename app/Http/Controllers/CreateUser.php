<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class CreateUser extends Controller
{
    public static function store()
    {
        DB::beginTransaction();
        try{
            DB::table('users')->insert([
                'name'          => 'Your Name',
                'tempat_lahir'  => 'Place of Birth',
                'tgl_lahir'     => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
                'address'       => 'Your Address',
                'email'         => 'your@email.com',
                'username'      => 'admin',
                'password'      => bcrypt('123456'),
                'level'         => 1,
                'is_active'     => 1,
                'img'           => null,
                'login'         => null,
                'logout'        => null,
                'created_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_at'     => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
            ]);
            DB::commit();
            return 'TRUE';
        }catch (\Exception $e){
            DB::rollback();
            return 'Failed creating user admin => ' . $e;
        }
    }
}