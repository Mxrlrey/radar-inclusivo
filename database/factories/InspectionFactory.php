<?php

namespace Database\Factories;

use App\Models\Inspection;
use App\Models\Barrier;
use App\Models\AccessibleEducationalMaterial;
use App\Models\AssistiveTechnology;
use App\Models\User;
use App\Enums\BarrierStatus;
use App\Enums\ConservationState;
use App\Enums\InspectionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{
    protected $model = Inspection::class;

    public function configure(): static
    {
        return $this->forAssistiveTechnology();
    }

    public function definition(): array
    {
        return [
            'state'           => ConservationState::GOOD->value,
            'status'          => null,
            'inspection_date' => now(),
            'description'     => $this->faker->optional()->sentence(),
            'type'            => $this->faker->randomElement([
                InspectionType::INITIAL->value,
                InspectionType::PERIODIC->value,
            ]),
            'user_id'         => User::factory(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Barrier Inspection
    |--------------------------------------------------------------------------
    | - NÃO usa state
    | - SEMPRE usa status
    */
    public function forBarrier(?Barrier $barrier = null): static
    {
        return $this->state(function () {
            return [
                'state'  => null,
                'status' => $this->faker->randomElement(BarrierStatus::cases())->value,
                'type'   => $this->faker->randomElement([
                    InspectionType::INITIAL->value,
                    InspectionType::PERIODIC->value,
                ]),
            ];
        })->for($barrier ?? Barrier::factory(), 'inspectable');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessible Educational Material (MPA)
    |--------------------------------------------------------------------------
    | - USA state
    | - NÃO usa status
    */
    public function forAccessibleEducationalMaterial(?AccessibleEducationalMaterial $material = null): static
    {
        return $this->state(function () {
            return [
                'state'  => $this->faker->randomElement(ConservationState::cases())->value,
                'status' => null,
            ];
        })->for($material ?? AccessibleEducationalMaterial::factory(), 'inspectable');
    }

    /*
    |--------------------------------------------------------------------------
    | Assistive Technology
    |--------------------------------------------------------------------------
    | - USA state
    | - NÃO usa status
    */
    public function forAssistiveTechnology(?AssistiveTechnology $at = null): static
    {
        return $this->state(function () {
            return [
                'state'  => $this->faker->randomElement(ConservationState::cases())->value,
                'status' => null,
            ];
        })->for($at ?? AssistiveTechnology::factory(), 'inspectable');
    }
}
