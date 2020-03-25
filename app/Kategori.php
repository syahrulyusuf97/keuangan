<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table       = 'ms_kategori';
    protected $primaryKey  = 'id';
    const CREATED_AT       = 'created_at';
    const UPDATED_AT       = 'updated_at';

    protected $fillable = ['id','jenis_transaksi', 'nama', 'iduser', 'created_at', 'updated_at', 'warna'];
}
