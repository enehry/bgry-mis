<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlotterRecords extends Model
{
  use SoftDeletes;
  protected $table = 'blotter_records';

  protected $fillable = [
    'user_id',
    'name',
    'type',
    'size'
  ];
  use HasFactory;
}
