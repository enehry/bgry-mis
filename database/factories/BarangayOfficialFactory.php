<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BarangayOfficialFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */



  public function definition()
  {
    return [
      //
      'name' => fake()->name,
      'age' => fake()->numberBetween(18, 60),
      'birthdate' => fake()->date(),
      'gender' => fake()->randomElement(['Male', 'Female']),
      'position' => fake()->word(),
      'phone_number' => fake()->phoneNumber(),
      'email' => fake()->safeEmail(),
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ];
  }
}
