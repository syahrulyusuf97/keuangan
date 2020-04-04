<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kebijakan extends Model
{
    protected $table       = 'kebijakan';
    protected $primaryKey  = 'id';
    const CREATED_AT       = 'created_at';
    const UPDATED_AT       = 'updated_at';
}
