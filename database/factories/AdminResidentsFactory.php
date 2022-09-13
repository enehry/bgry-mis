<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AdminResidentsFactory extends Factory
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
      'first_name' => fake()->firstName(),
      'middle_name' => fake()->lastName(),
      'last_name' => fake()->lastName(),
      'nickname' => fake()->firstName(),
      'place_of_birth' => fake()->city(),
      'birthdate' => fake()->date(),
      'age' => fake()->numberBetween(18, 65),
      'civil_status' =>  fake()->randomElement(['Married, Widowed, Single']),
      'street' => fake()->streetName(),
      'gender' => fake()->randomElement(['Male', 'Female']),
      'voter_status' => fake()->randomElement(['Active', 'Inactive']),
      'citizenship' => 'Filipino',
      'email' => fake()->safeEmail(),
      'phone_number' => fake()->phoneNumber(),
      'occupation' => fake()->jobTitle(),
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password


    ];
  }
}
