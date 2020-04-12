<?php

use App\Models\Renter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RenterFakerSeeder extends Seeder
{
    const LOCALE = 'ru_RU';
    const NUMBER = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create(self::LOCALE);

        for ($i=0;  $i<self::NUMBER; $i++) {
            $renter = new Renter();

            $company = $faker->company;


            $renter->fill([
                'title'       => $company,
                'phone'      => $faker->e164PhoneNumber,
                'email'      =>
                    $faker->randomElement(['mail', 'info', 'box', 'contact']) . '@' .
                    Str::slug(Str::afterLast($company, ' ')) . '.' .
                    $faker->tld,
                'description' => $faker->sentence,
                'color_id'   => 1,
            ]);

            $renter->save();
        }
    }
}
