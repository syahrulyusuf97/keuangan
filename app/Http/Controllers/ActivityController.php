<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use DB;

class ActivityController extends Controller
{
    public static function log($user, $action, $title, $note, $oldnote, $date)
    {
        DB::beginTransaction();
        try{
            $activity = new Activity();
            $activity->iduser = $user;
            $activity->action = $action;
            $activity->title = $title;
            $activity->note = $note;
            $activity->oldnote = $oldnote;
            $activity->date = $date;
            $activity->save();
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return $e;
        }
    }
}
