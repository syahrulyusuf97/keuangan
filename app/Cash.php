<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table       = 'cash';
    protected $primaryKey  = 'c_id';
    const CREATED_AT       = 'created_at';
    const UPDATED_AT       = 'updated_at';

    protected $fillable = ['c_id','c_transaksi', 'c_jumlah', 'c_jenis', 'c_kategori', 'c_akun', 'created_at', 'updated_at'];

    public function akun()
    {
    	return $this->belongsTo("App\Akun", "c_akun", "id");
    }

    public function kategori()
    {
    	return $this->belongsTo("App\Kategori", "c_kategori", "id");
    }
}
