<?php

namespace App\Http\Controllers\AdminSide;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ActivityLog;
use App\Models\File;
use App\Models\BlotterRecords;
use App\Models\FinancialReport;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Support\Facades\Auth;

class FilesController extends Controller
{
  /**
   * Display a listing of the resource.
   *  
   * @return \Illuminate\Http\Response
   */

  /**Accomplishment Report*/
  public function reports(Request $request, $category)
  {
    $request->validate([
      'show' => 'nullable|string|in:yes,no',
    ]);

    $files = File::where('category', '=', $category ?? 'accomplishment')
      ->when($request->show == 'yes', function ($query) {
        return $query->onlyTrashed();
      })
      ->get();

    return view('admin.reports', ['files' =>  $files, 'category' => $category]);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function create()
  {
    return view('admin.create');
  }

  public function deletefile($id)
  {
    $file = File::find($id);
    $file->delete();

    ActivityLog::log(
      'deleted file with id ' . $file->id . ' ' . $file->name,
      'files',
      $file->id,
    );

    return back()->with('message', 'File Deleted');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  StoreFileRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreFileRequest $request, $category)
  {
    $fileName = $request->file->getClientOriginalName();

    $type = $request->file->getClientMimeType();
    $size = $request->file->getSize();

    // rename the file
    $fileName = md5(uniqid()) . '_' . $fileName;

    $request->file->move(public_path('file'), $fileName);



    $file = File::create([
      // 'user_id' => auth()->id(),
      'category' => $category,
      'name' => $fileName,
      'type' => $type,
      'size' => $size,
    ]);

    ActivityLog::log(
      'uploaded a file ' . $fileName,
      'files',
      $file->id
    );

    return back()->withSuccess(__('File added successfully.'));
  }

  public function download(Request $request, $file)
  {
    return response()->download(public_path('assets/' . $file));
  }

  public function viewFile($id)
  {
    $file = File::find($id);
    return view('admin.file', ['file' => $file]);
  }

  /*For Financial Report */
  public function financialreport()
  {
    $files2 = FinancialReport::all();
    return view('admin.financialreport', ['files2' => $files2]);
  }

  public function create2()
  {
    return view('admin.create2');
  }

  public function deletefile2($id)
  {
    $file2 = FinancialReport::find($id);
    $file2->delete();
    return back()->with('message', 'File Deleted');
  }

  public function store2(StoreFileRequest $request)
  {
    $fileName = $request->file->getClientOriginalName();
    $type = $request->file->getClientMimeType();
    $size = $request->file->getSize();
    $request->file->move(public_path('file'), $fileName);

    FinancialReport::create([
      // 'user_id' => auth()->id(),
      'name' => $fileName,
      'type' => $type,
      'size' => $size
    ]);

    return redirect('/financialreport')->withSuccess(__('File added successfully.'));
  }
  /*For Blotter Records */
  public function blotterrecord()
  {
    $files3 = BlotterRecords::all();
    return view('admin.blotterrecord', ['files3' => $files3]);
  }

  public function create3()
  {
    return view('admin.create3');
  }

  public function deletefile3($id)
  {
    $file3 = BlotterRecords::find($id);
    $file3->delete();
    return back()->with('message', 'File Deleted');
  }

  public function store3(StoreFileRequest $request)
  {
    $fileName = $request->file->getClientOriginalName();
    $type = $request->file->getClientMimeType();
    $size = $request->file->getSize();
    $request->file->move(public_path('file'), $fileName);

    BlotterRecords::create([
      // 'user_id' => auth()->id(),
      'name' => $fileName,
      'type' => $type,
      'size' => $size
    ]);

    return redirect('/blotterrecord')->withSuccess(__('File added successfully.'));
  }
}
