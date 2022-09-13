<?php

namespace App\Http\Controllers\Helper;

use App\Models\ActivityLog as ModelsActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLog
{

  public static function log($user, $action, $description)
  {
    $log = new ModelsActivityLog();
    $log->user_id = Auth::user()->id;
    $log->action = $action;
    $log->description = $description;
    $log->save();
  }
}
