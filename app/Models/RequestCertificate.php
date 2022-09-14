<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCertificate extends Model
{
  use HasFactory;
  protected $fillable = [
    'admin_resident_id',
    'fullname',
    'docType',
    'date',
    'paymentMethod',
    'referenceNumber',
    'purpose',
    'screenshot',
    'status',
  ];

  public function admin_resident()
  {
    return $this->belongsTo(AdminResidents::class);
  }
}
