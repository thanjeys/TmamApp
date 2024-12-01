<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'organization_id' => $this->faker->unique()->randomNumber(),
            'name' => $this->faker->company,
            'contact_name' => $this->faker->name,
            'email' => $this->faker->companyEmail,
            'is_default_org' => $this->faker->boolean,
            'language_code' => $this->faker->languageCode,
            'fiscal_year_start_month' => $this->faker->numberBetween(1, 12),
            'account_created_date' => $this->faker->date(),
            'time_zone' => $this->faker->timezone,
            'is_org_active' => $this->faker->boolean,
            'currency_id' => $this->faker->randomNumber(),
            'currency_code' => $this->faker->currencyCode,
            'currency_symbol' => '',
        ];
    }
}
