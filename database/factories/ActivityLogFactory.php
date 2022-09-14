<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
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
      'user_id' => $this->faker->numberBetween(1, 10),
      'action' => $this->faker->paragraph(2),
      'table_name' => $this->faker->word,
      'table_id' => $this->faker->numberBetween(1, 10),
    ];
  }
}
