<?php

namespace App\Http\Controllers\Helper;

use App\Models\ActivityLog as ModelsActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLog
{

  public static function log($table_name, $action, $table_id)
  {
    $log = new ModelsActivityLog();
    $log->user_id = Auth::user()->id;
    $log->table_name = $table_name;
    $log->action = $action;
    $log->table_id = $table_id;
    $log->save();
  }
}
