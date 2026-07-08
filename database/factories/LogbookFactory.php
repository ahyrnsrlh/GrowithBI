<?php

namespace Database\Factories;

use App\Models\Logbook;
use App\Models\User;
use App\Models\Division;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Logbook>
 */
class LogbookFactory extends Factory
{
    protected $model = Logbook::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'division_id' => Division::factory(),
            'title' => $this->faker->sentence(),
            'date' => $this->faker->date(),
            'duration' => 8.0,
            'time_in' => '08:00',
            'time_out' => '17:00',
            'activities' => $this->faker->paragraph(),
            'learning_points' => $this->faker->paragraph(),
            'status' => 'submitted',
        ];
    }
}
