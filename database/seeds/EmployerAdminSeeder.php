<?php

use App\Models\Employer;
use Illuminate\Database\Seeder;

class EmployerAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employer = new Employer();

        $employer->fill([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'phone'      => '+79876543210',
            'email'      => 'john.doe@example.com',
            'color_id'   => 1,
            'password'   => app('hash')->make('Doe007'),
        ]);

        $employer->save();
    }
}
