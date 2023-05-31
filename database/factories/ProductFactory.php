<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class ProductFactory extends Factory
{
    use WithFaker;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                   => $this->faker->text('100'),
            'sku'                    => $this->faker->unique()->numerify('#######'),
            'price'                  => $this->faker->numberBetween(1, 5000),
            'product_category_id'    => ProductCategory::pluck('id')->random(),
            'tags'                   => [$this->faker->lexify, $this->faker->lexify],
            'description'            => $this->faker->realTextBetween(),
            'additional_information' => $this->faker->realTextBetween(),
            'rate'                   => $this->faker->numberBetween(0, 5),
            'images'                 => [$this->faker->imageUrl(), $this->faker()->imageUrl()],
        ];
    }
}
