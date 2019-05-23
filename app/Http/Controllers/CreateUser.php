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
                'img'           => null,
                'login'         => null,
                'logout'        => null
            ]);
            DB::commit();
            return 'Successfully created user admin';
        }catch (\Exception $e){
            DB::rollback();
            return 'Failed creating user admin => ' . $e;
        }
    }
}