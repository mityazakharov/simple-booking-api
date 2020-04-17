<?php

use App\Models\Color;
use App\Models\Employer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployerFakerSeeder extends Seeder
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

        $domain = $faker->safeEmailDomain;
        $colors = Color::all()->pluck('id')->all();

        for ($i=0;  $i<self::NUMBER; $i++) {
            $employer = new Employer();

            $gender = $faker->randomElement(['male','female']);

            $employer->fill([
                'first_name'  => $faker->firstName($gender),
                'middle_name' => self::LOCALE == 'ru_RU' ? $faker->middleName($gender) : null,
                'last_name'   => $faker->lastName($gender),
                'phone'       => $faker->e164PhoneNumber,
                'email'       =>
                    Str::slug($employer->first_name, '-') . '.' .
                    Str::slug($employer->last_name, '-') . '@' .
                    $domain,
                'color_id'    => $faker->randomElement($colors),
            ])->save();
        }
    }
}
