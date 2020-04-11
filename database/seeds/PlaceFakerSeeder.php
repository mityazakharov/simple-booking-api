<?php

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceFakerSeeder extends Seeder
{
    const LOCALE = 'ru_RU';
    const NUMBER = 5;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create(self::LOCALE);

        for ($i=0;  $i<self::NUMBER; $i++) {
            $place = new Place();

            $title = $faker->randomElement(['city','country']);
            $length = $faker->numberBetween(50, 250);
            $index = $faker->numberBetween(1, 4);

            $place->fill([
                'title'       => $faker->{$title},
                'description' => $faker->realText($length, $index),
            ]);

            $place->save();
        }
    }
}
