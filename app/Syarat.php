<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Syarat extends Model
{
    protected $table       = 'syarat';
    protected $primaryKey  = 'id';
    const CREATED_AT       = 'created_at';
    const UPDATED_AT       = 'updated_at';
}
