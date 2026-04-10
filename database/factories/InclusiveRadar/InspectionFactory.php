<?php

namespace Database\Factories\InclusiveRadar;

use App\Enums\BarrierStatus;
use App\Enums\ConservationState;
use App\Enums\InspectionType;
use App\Models\AccessibleEducationalMaterial;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{
    protected $model = \App\Models\Inspection::class;

    public function definition(): array
    {
        return [
            'state'           => ConservationState::NOT_APPLICABLE->value,
            'status'          => \App\Enums\BarrierStatus::IDENTIFIED->value,
            'inspection_date' => now(),
            'description'     => $this->faker->optional()->sentence(),
            'type'            => $this->faker->randomElement(InspectionType::cases())->value,
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
    public function forBarrier(?\App\Models\Barrier $barrier = null): static
    {
        return $this->state(function () {
            return [
                'state'  => null,
                'status' => $this->faker->randomElement(BarrierStatus::cases())->value,
                'type'   => $this->faker->randomElement([
                    \App\Enums\InspectionType::INITIAL->value,
                    InspectionType::PERIODIC->value,
                ]),
            ];
        })->for($barrier ?? \App\Models\Barrier::factory(), 'inspectable');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessible Educational Material (MPA)
    |--------------------------------------------------------------------------
    | - USA state
    | - NÃO usa status
    */
    public function forAccessibleEducationalMaterial(?\App\Models\AccessibleEducationalMaterial $material = null): static
    {
        return $this->state(function () {
            return [
                'state'  => $this->faker->randomElement(\App\Enums\ConservationState::cases())->value,
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
    public function forAssistiveTechnology(?\App\Models\AssistiveTechnology $at = null): static
    {
        return $this->state(function () {
            return [
                'state'  => $this->faker->randomElement(ConservationState::cases())->value,
                'status' => null,
            ];
        })->for($at ?? \App\Models\AssistiveTechnology::factory(), 'inspectable');
    }
}
