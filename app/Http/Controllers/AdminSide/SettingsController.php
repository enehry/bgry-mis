<?php

namespace App\Http\Controllers\AdminSide;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ActivityLog;
use App\Models\settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

  //Settings
  public function settings()
  {
    $setting = settings::all();
    return view('admin.settings', ['setting' => $setting]);
  }


  //Update Barangay Officialss
  public function updateSettings(Request $request, settings $setting)
  {

    $formFields = $request->validate([
      'barangay_name' => 'required',

    ]);
    if ($request->hasFile('barangay_logo')) {
      $formFields['barangay_logo'] = $request->file('barangay_logo')->store('images', 'public');
    }
    $setting->update($formFields);


    ActivityLog::log(
      'updated settings with id ' . $setting->id . ' ' . $setting->barangay_name,
      'settings',
      $setting->id,
    );
    return back()->with('message', 'Update Successful');
  }
}
