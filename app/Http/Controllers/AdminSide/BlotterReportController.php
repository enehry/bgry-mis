<?php

namespace App\Http\Controllers\AdminSide;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Blotter;

class BlotterReportController extends Controller
{
  public function blotter()
  {
    $blo = Blotter::all();
    return view('admin.blotter', ['blo' => $blo]);
  }
  public function deleteBlotter($id)
  {
    $blotter = Blotter::find($id);

    $blotter->delete();

    ActivityLog::log(
      'deleted blotter with id ' . $blotter->id . ' complainant ' . $blotter->complainant,
      'blotter',
      $blotter->id,
    );

    return back()->with('message', 'Blotter Case Deleted');
  }
  //Approve
  public function submitBlotter($id)
  {
    $blo = Blotter::find($id);
    if ($blo->estado == 'pending') {
      $blo->estado = 'approved';
      $blo->save();

      ActivityLog::log(
        'approved blotter with id ' . $blo->id . ' complainant ' . $blo->complainant,
        'blotter',
        $blo->id,
      );
    } else if ($blo->estado == 'declined') {
      return back()->with('message', 'Blotter Case Already Declined');
    }

    return view('admin.blotterLetter', ['blo' => $blo]);
  }

  public function declineBlotter($id)
  {
    $blo = Blotter::find($id);
    if ($blo->estado == 'pending') {
      $blo->estado = 'declined';
      $blo->save();

      ActivityLog::log(
        'declined blotter with id ' . $blo->id . ' complainant ' . $blo->complainant,
        'blotter',
        $blo->id,
      );
      return back()->with('message', 'Blotter Case Declined');
    } else {
      return back()->with('message', 'Blotter Case Already Approved/Declined');
    }
    return view('admin.blotterLetter', ['blo' => $blo]);
  }
}
