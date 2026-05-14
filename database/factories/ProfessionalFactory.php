<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Position;
use App\Models\Professional;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionalFactory extends Factory
{
    protected $model = Professional::class;

    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'position_id' => Position::factory(),
            'registration' => $this->faker->unique()->numerify('PROF######'),
            'entry_date' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    public function active(): self
    {
        return $this->state(fn () => [
            'is_active' => true,
        ]);
    }
}
