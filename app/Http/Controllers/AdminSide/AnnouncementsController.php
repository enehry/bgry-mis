<?php

namespace App\Http\Controllers\AdminSide;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Announcements;


class AnnouncementsController extends Controller
{
  public function announcements()
  {
    $ann = Announcements::all();
    return view('admin.announcements', ['ann' => $ann]);
  }

  //Announcement Storing Data
  public function announcementStore(Request $request)
  {

    $formFields = $request->validate([
      'title' => 'required',
      'description' => 'required',
      'content' => 'required',
    ]);


    $announcement = Announcements::create($formFields);

    ActivityLog::log(
      'created announcement with id ' . $announcement->id . ' ' . $announcement->title,
      'announcements',
      $announcement->id,
    );
    return back()->with('message', 'Announcement Created Successfuly');
  }


  //Announcement Update Data
  public function updateAnnouncements(Request $request, Announcements $announcement)
  {

    $formFields = $request->validate([
      'title' => 'required',
      'description' => 'required',
      'content' => 'required',
    ]);


    $announcement->update($formFields);
    ActivityLog::log(
      'updated announcement with id ' . $announcement->id . ' ' . $announcement->title,
      'announcements',
      $announcement->id,
    );

    return back()->with('message', 'Announcement Updated Successfuly');
  }

  public function deleteAnnouncements($id)
  {
    $annou = Announcements::find($id);

    $annou->delete();

    ActivityLog::log(
      'deleted announcement with id ' . $annou->id . ' ' . $annou->title,
      'announcements',
      $annou->id,
    );

    return back()->with('message', 'Announcement Deleted Succesfull');
  }
}
