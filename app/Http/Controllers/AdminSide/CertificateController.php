<?php

namespace App\Http\Controllers\AdminSide;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ActivityLog;
use App\Models\AdminResidents;
use App\Models\barangayOfficial;
use App\Models\Certificate;
use App\Models\RequestCertificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
  public function certificateOfResidency(Request $request)
  {
    $request->validate([
      'search' => 'nullable|string|max:255',
    ]);
    $request->validate([
      'search' => 'nullable|string|max:255',
      'status' => 'nullable|string|in:pending,approved,declined',
    ]);

    $status = $request->status ?? 'pending';

    $res = RequestCertificate::where('doctype', '=', 'Certificate of Residency')
      ->where('status', '=', $status)
      ->when($request->search, function ($query) use ($request) {
        return $query->where('fullname', 'like', '%' . $request->search . '%');
      })->get();
    return view('admin.certificateOfResidency', ['res' => $res]);
  }

  public function certificateOfIndigency(Request $request)
  {
    $request->validate([
      'search' => 'nullable|string|max:255',
      'status' => 'nullable|string|in:pending,approved,declined',
    ]);

    $status = $request->status ?? 'pending';
    $ind = RequestCertificate::where('doctype', '=', 'Certificate of Indigency')
      ->where('status', '=', $status)
      ->when($request->search, function ($query) use ($request) {
        return $query->where('fullname', 'like', '%' . $request->search . '%');
      })->get();
    return view('admin.certificateOfIndigency', ['ind' => $ind]);
  }

  public function certificateOfClearance(Request $request)
  {
    $request->validate([
      'search' => 'nullable|string|max:255',
      'status' => 'nullable|string|in:pending,approved,declined',
    ]);

    $status = $request->status ?? 'pending';

    $clear = RequestCertificate::where('doctype', '=', 'Barangay Clearance')
      ->where('status', $status)
      ->when($request->search, function ($query) use ($request) {
        return $query->where('fullname', 'like', '%' . $request->search . '%');
      })->get();
    return view('admin.certificateOfClearance', ['clear' => $clear]);
  }

  public function barangayClearance($id)
  {


    $certificate = RequestCertificate::find($id);
    if ($certificate->status == 'pending') {
      $certificate->status = 'approved';
      $certificate->save();
      ActivityLog::log(
        'approved barangay' . $certificate->doctype  . ' with id ' . $certificate->id . ' ' . $certificate->fullname,
        'certificate',
        $certificate->id,
      );
    } else if ($certificate->status == 'declined') {
      return redirect()->back()->with('success', 'Request is already declined');
    }
    $cer =  $certificate->admin_resident;
    return view('admin.barangayClearance', ['cer' => $cer]);
  }

  public function barangayResidency($id)
  {
    $certificate = RequestCertificate::find($id);
    if ($certificate->status == 'pending') {
      $certificate->status = 'approved';
      $certificate->save();

      ActivityLog::log(
        'approved barangay' . $certificate->doctype  . ' with id ' . $certificate->id . ' ' . $certificate->fullname,
        'certificate',
        $certificate->id,
      );
    } else if ($certificate->status == 'declined') {
      return redirect()->back()->with('success', 'Request is already declined');
    }
    $cer =  $certificate->admin_resident;
    return view('admin.barangayResidency', ['cer' => $cer]);
  }

  public function barangayIndigency($id)
  {
    // $cer = AdminResidents::find($id);
    // return view('admin.barangayIndigency', ['cer' => $cer]);

    $certificate = RequestCertificate::find($id);
    if ($certificate->status == 'pending') {
      $certificate->status = 'approved';
      $certificate->save();

      ActivityLog::log(
        'approved barangay' . $certificate->doctype  . ' with id ' . $certificate->id . ' ' . $certificate->fullname,
        'certificate',
        $certificate->id,
      );
    } else if ($certificate->status == 'declined') {
      return redirect()->back()->with('success', 'Request is already declined');
    }
    $cer =  $certificate->admin_resident;



    return view('admin.barangayIndigency', ['cer' => $cer]);
  }



  public function deleteRequest($id)
  {
    $cer = RequestCertificate::find($id);



    $cer->delete();

    ActivityLog::log(
      'deleted request ' . $cer->doctype . ' with id ' . $cer->id . ' ' . $cer->fullname,
      'certificate',
      $cer->id,
    );

    return back()->with('message', 'Request Deleted');
  }



  // public function barangayResidency($id){
  //     $cer = AdminResidents::find($id);
  //     return view('admin.barangayIndigency',['cer'=>$cer]);
  //     }
  public function certificateStore(Request $request, Certificate $certificate)
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
      'email' => ['required', 'email'],
      'phone_number' =>  'required',
      'occupation' => 'required',
      'password' => 'required'
    ]);

    if ($request->hasFile('profile_image')) {
      $formFields['profile_image'] = $request->file('profile_image')->store('images', 'public');
    }
    $certificate->update($formFields);
    return back()->with('message', 'Update Successful');
  }

  // PANG DECLINED NG CERTIFICATE
  public function declineCertificate($id)
  {
    $certificate = RequestCertificate::find($id);
    if ($certificate->status == 'pending') {
      $certificate->status = 'declined';
      $certificate->save();

      ActivityLog::log(
        'declined ' . $certificate->doctype . ' with id ' . $certificate->id . ' ' . $certificate->fullname,
        'certificate',
        $certificate->id,
      );
    }
    return back()->with('message', 'Request Declined');
  }
}
