<?php

namespace App\Http\Controllers\AdminSide;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\barangayOfficial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BarangayOfficialController extends Controller
{
  public function listBrgyOfficial()
  {
    $official = barangayOfficial::all();
    return view('admin.listBarangayOfficial', ['official' => $official]);
  }
  //Form Barangay Official
  public function official()
  {
    return view('admin.formAddOfficial');
  }

  //Barangay Official Storing Data
  public function storeOfficial(Request $request)
  {

    $formFields = $request->validate([
      'name' => 'required',
      'age' => 'required',
      'birthdate' => 'required',
      'gender' => 'required',
      'position' => 'required',
      'phone_number' => 'required',
      'email' => 'required',
      'password' => 'required',
      'official_image' => 'required',

    ]);
    $formFields['password'] = bcrypt($formFields['password']);

    if ($request->hasFile('official_image')) {
      $formFields['official_image'] = $request->file('official_image')->store('images', 'public');
    }
    $official = barangayOfficial::create($formFields);

    ActivityLog::log(
      'barangay_officials',
      'created barangay official with id ' . $official->id . ' ' . $official->name,
      $official->id,
    );
    return redirect('/listBrgyOfficial')->with('message', 'Barangay Official Created Successfuly');
  }

  //Delete Residents
  public function deleteOfficial($id)
  {
    $official = barangayOfficial::find($id);

    $official->delete();

    ActivityLog::log(
      'barangay_officials',
      'Deleted barangay official with id ' . $official->id . ' ' . $official->name,
      $official->id,
    );
    return back()->with('message', 'Barangay Official Profile Deleted');
  }


  //Update Barangay Officialss
  public function updateOfficial(Request $request, barangayOfficial $official)
  {

    $formFields = $request->validate([
      'name' => 'required',
      'age' => 'required',
      'birthdate' => 'required',
      'gender' => 'required',
      'position' => 'required',
      'phone_number' => 'required',
      'email' => 'required'

    ]);
    if ($request->hasFile('official_image')) {
      $formFields['official_image'] = $request->file('official_image')->store('images', 'public');
    }
    $official->update($formFields);

    ActivityLog::log(
      'barangay_officials',
      'Updated barangay official with id ' . $official->id . ' ' . $official->name,
      $official->id,
    );

    return back()->with('message', 'Update Successful');
  }

  //Login Page
  public function login()
  {

    return view('adminLoginPage');
  }
  public function adminLogin(Request $request)
  {

    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => 'required',
    ]);

    // if(auth()->attempt($formFields)) {
    //     $request->session()->regenerate();

    //     return redirect('/dashboard')->with('message', 'You are now
    //     logged in!');
    //     }
    //     return back()->withErrors(['email'=>'Invalid Credentials'])->onlyInput('email');


    if (Auth::guard('barangay_official')->attempt($credentials)) {
      Auth::login(Auth::guard('barangay_official')->user());
      ActivityLog::log(
        'barangay_officials',
        'Logged in barangay official with id ' . Auth::user()->id . ' ' . Auth::user()->name,
        Auth::user()->id,
      );
      return redirect('/dashboard');
    } else {

      return back()->with('fail', ' This email is not registered. ');
    }
  }

  //logout
  public function logout(Request $request)
  {

    ActivityLog::log(
      'barangay_officials',
      'Logged out barangay official with id ' . Auth::guard('barangay_official')->user()->id . ' ' . Auth::guard('barangay_official')->user()->name,
      Auth::guard('barangay_official')->user()->id,
    );

    // logout all sessions
    Auth::guard('barangay_official')->logout();
    Auth::logout();

    // $request->session()->invalidate();
    // $request->session()->regenerateToken();

    return redirect('/')->with('message', ' Youre Logout');
  }
}
