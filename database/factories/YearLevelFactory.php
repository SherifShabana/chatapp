<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\YearLevel>
 */
class YearLevelFactory extends Factory
{

    private static $deptid = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        if (self::$deptid == 4) {
            self::$deptid = 1;
        }

        return [
            'name' => fake()->name(),
            'department_id' => self::$deptid++,
        ];
    }
}
