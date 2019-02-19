<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table       = 'activity';
    protected $primaryKey  = 'id';

    protected $fillable = ['id','iduser', 'action', 'title', 'note', 'oldnote', 'date'];

}
