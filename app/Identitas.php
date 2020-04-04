<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Identitas extends Model
{
    protected $table       = 'identitas_app';
    protected $primaryKey  = 'id';
    const CREATED_AT       = 'created_at';
    const UPDATED_AT       = 'updated_at';
}
