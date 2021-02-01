<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make($this->faker->password(8, 12)),
            'acceptTerms' => $this->faker->boolean(),
            'newsletter' => $this->faker->boolean(),
            'discount_coupons' => $this->faker->boolean(),
            'confirmation_email' => $this->faker->boolean(),

        ];
    }
}
