<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table       = 'ms_akun';
    protected $primaryKey  = 'id';
    const CREATED_AT       = 'created_at';
    const UPDATED_AT       = 'updated_at';

    protected $fillable = ['id','kode_akun', 'nama_akun', 'jenis_akun', 'iduser', 'created_at', 'updated_at'];
}
