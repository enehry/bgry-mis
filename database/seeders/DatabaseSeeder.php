<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AdminResidents;
use App\Models\barangayOfficial;
use App\Models\settings;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    \App\Models\User::factory(3)->create();

    // \App\Models\User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com'
    // ]);

    barangayOfficial::factory(20)->create();
    AdminResidents::factory(20)->create();

    settings::create([
      'barangay_name' => 'Barangay Name',
      'barangay_logo' => '/assets/imageee.png'
    ]);
  }
}
