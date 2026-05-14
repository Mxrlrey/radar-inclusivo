<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'registration' => $this->faker->unique()->numerify('MAT######'),
            'entry_date' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
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
