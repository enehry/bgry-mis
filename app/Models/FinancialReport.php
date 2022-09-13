<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialReport extends Model
{

  use SoftDeletes;
  protected $table = 'financial_reports';

  protected $fillable = [
    'user_id',
    'name',
    'type',
    'size'
  ];
  use HasFactory;
}
