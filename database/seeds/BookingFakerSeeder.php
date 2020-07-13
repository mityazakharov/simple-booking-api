<?php

use App\Models\Booking;
use App\Models\Client;
use App\Models\Employer;
use App\Models\Place;
use App\Models\Renter;
use App\Models\Status;
use Illuminate\Database\Seeder;

class BookingFakerSeeder extends Seeder
{
    const LOCALE = 'ru_RU';
    const NUMBER = 50;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create(self::LOCALE);

        $statuses = Status::all()->pluck('id')->all();
        $clients = Client::all()->pluck('id')->all();

        // agent
        $employers = Employer::all();
        $renters = Renter::all();

        $places = Place::all()->pluck('id')->all();

        for ($i=0;  $i<self::NUMBER; $i++) {
            $booking = new Booking();

            // begin-end
            $date = $faker->dateTimeThisYear(date('Y-12-31'))->format('Y-m-d 10:00');
            $time = $faker->dateTimeInInterval($date, '+8 hours');
            $hour = $time->format('H');
            $minute = $faker->randomElement(['0', '30']);
            $begin_at = $time->setTime($hour, $minute);
            $end_at = clone $begin_at;
            $interval = new DateInterval($faker->randomElement(['PT60M', 'PT90M', 'PT120M']));
            $end_at = $end_at->add($interval);


            if ($client = $faker->optional($weight = 0.8)->randomElement($clients)) {
                $agent = $faker->randomElement($employers);
                $agent_type = $agent->getTable();
                $agent_id = $agent->id;

                $length = $faker->numberBetween(20, 60);
                $index = $faker->numberBetween(2, 4);
                $info = $faker->realText($length, $index);
            } else {
                $agent = $faker->randomElement($renters);
                $agent_type = $agent->getTable();
                $agent_id = $agent->id;

                $info = null;
            }

            $booking->fill([
                'begin_at'   => $begin_at,
                'end_at'     => $end_at,
                'status_id'  => $faker->randomElement($statuses),
                'client_id'  => $client,
                'agent_type' => $agent_type,
                'agent_id'   => $agent_id,
                'place_id'   => $faker->randomElement($places),
                'info'       => $info,
            ])->save();
        }
    }
}
