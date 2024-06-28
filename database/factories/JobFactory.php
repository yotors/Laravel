<?php

namespace Database\Factories;

use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employer_id'=>Employer::factory(),
            'title'=>fake()->jobTitle(),
            'salary'=>fake()->randomElement(['$50,000 USD','$45,000 USD','$60,000 USD']),
            'location'=>'Remote',
            'Schedule'=>'Full Time',
            'url'=>fake()->url(),
            'featured'=>false,
            'discription'=>fake()->randomElement(['Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde, optio voluptatem neque laborum perferendis necessitatibus sunt nostrum quibusdam ullam molestias incidunt est? Porro beatae dolorum quae labore soluta pariatur nemo.','met consectetur adipisicing elit. Unde, optio voluptatem neque laborum perferendis necessitatibus sunt nostrum quibusdam ullam molestias incidunt est? Porro beatae dolorum quae labore soluta pariatur nemo.','consectetur adipisicing elit. Unde, optio voluptatem neque laborum perferendis necessitatibus sunt Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde, optio voluptatem neque laborum perferendis necessitatibus sunt nostrum quibusdam ullam molestias incidunt est? Porro beatae dolorum quae labore soluta pariatur nemo.nostrum quibusdam ullam molestias incidunt est? Porro beatae dolorum quae labore soluta pariatur nemo.'])
        ];
        
    }
}
