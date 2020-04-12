<?php

use App\Models\Client;
use App\Models\Employer;
use Illuminate\Database\Seeder;

class ClientFakerSeeder extends Seeder
{
    const LOCALE = 'ru_RU';
    const NUMBER = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create(self::LOCALE);

        $employers = Employer::all()->pluck('id')->all();

        for ($i=0;  $i<self::NUMBER; $i++) {
            $client = new Client();

            $gender = $faker->randomElement(['male','female']);
            $length = $faker->numberBetween(20, 60);
            $index = $faker->numberBetween(2, 4);

            $client->fill([
                'name' => $faker->name($gender),
                'phone' => $faker->e164PhoneNumber,
                'email' => $faker->freeEmail,
                'newsletter' => $faker->randomElement([0, 1]),
                'info' => $faker->realText($length, $index),
            ]);

            $client->save();

            if ($num = $faker->optional(0.5)->numberBetween(1,3)) {
              $client->employers()->attach($faker->randomElements($employers, $num));
            }
        }
    }
}
