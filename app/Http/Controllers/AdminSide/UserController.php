<?php

namespace App\Http\Controllers\AdminSide;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ActivityLog;
use App\Models\ActivityLog as ModelsActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AdminResidents;
use Illuminate\Validation\Rule;
use App\Models\ResidentsRegistration;


class UserController extends Controller
{
  public function register()
  {
    return view('/register');
  }

  //Register Admin Residents Storing Data
  public function registerStore(Request $request)
  {

    $formFields = $request->validate([
      'first_name' => 'required',
      'middle_name' => 'required',
      'last_name' => 'required',
      'nickname' => 'required',
      'place_of_birth' => 'required',
      'birthdate' => 'required',
      'age' => 'required|before:13 years ago',
      'civil_status' => 'required',
      'street' => 'required',
      'gender' => 'required',
      'voter_status' => 'required',
      'citizenship' => 'required',
      'email' => ['required', 'email', Rule::unique('users', 'email')],
      'phone_number' =>  'required',
      'occupation' => 'required',
      'password' => 'required'

    ]);

    if ($request->hasFile('profile_image', '&&', 'image_id_birth')) {
      $formFields['profile_image'] = $request->file('profile_image')->store('images', 'public');
      $formFields['image_id_birth'] = $request->file('image_id_birth')->store('images', 'public');
    }

    $registration = ResidentsRegistration::create($formFields);

    ActivityLog::log(
      'residents_registrations',
      'created residents registration with id ' . $registration->id . ' ' . $registration->first_name,
      $registration->id,
    );

    return back()->with('message', 'Registration Complete');
  }

  //Residents registration 
  public function registration()
  {

    $reg = ResidentsRegistration::paginate(5);
    return view('Admin.registration', ['reg' => $reg]);
  }

  //View Residents
  public function viewRegistration($id)
  {

    $reg = ResidentsRegistration::find($id);
    return view('admin.viewRegistration', ['reg' => $reg]);
  }

  //Accept Registration in Admin and Store in database to create Account
  public function acceptRegistration(Request $request)
  {

    $formFields = $request->validate([
      'first_name' => 'required',
      'middle_name' => 'required',
      'last_name' => 'required',
      'nickname' => 'required',
      'place_of_birth' => 'required',
      'birthdate' => 'required',
      'age' => 'required|before:13 years ago',
      'civil_status' => 'required',
      'street' => 'required',
      'gender' => 'required',
      'voter_status' => 'required',
      'citizenship' => 'required',
      'email' => ['required', 'email', Rule::unique('users', 'email')],
      'phone_number' =>  'required',
      'occupation' => 'required',
      'password' => 'required'

    ]);
    $formFields['password'] = bcrypt($formFields['password']);

    if ($request->hasFile('profile_image', '&&', 'image_id_birth')) {
      $formFields['profile_image'] = $request->file('profile_image')->store('images', 'public');
      $formFields['image_id_birth'] = $request->file('image_id_birth')->store('images', 'public');
    } else {
      $formFields['resident_image'] = $request->oldprofile_image;
      $formFields['oldimage_id_birth'] = $request->oldimage_id_birth;
    }
    $reg = AdminResidents::create($formFields);

    ActivityLog::log(
      'admin_residents',
      'Created admin residents with id ' . $reg->id . ' ' . $reg->first_name,
      $reg->id,
    );


    return redirect('/residents')->with('message', 'Residents Registration Accepted');
  }

  public function activityLog(Request $request)
  {

    $request->validate([
      'search' => 'nullable|string',
      'perPage' => 'nullable|integer',
      'page' => 'nullable|integer',
      'sortBy' => 'nullable|string',
    ]);

    $perPage = $request->perPage ?? 10;
    $sort = explode(' ',  $request->sortBy ?? 'created_at desc');
    $column = $sort[0];
    $direction = $sort[1];

    $logs = ModelsActivityLog::when($request->search, function ($query, $search) {
      return $query->where('action', 'like', '%' . $search . '%');
    })
      ->orderBy($column, $direction)
      ->paginate($perPage);



    return view('admin.activitylog', ['logs' => $logs]);
  }
}
