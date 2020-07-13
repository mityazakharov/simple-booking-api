<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Mandatory
         $this->call('ColorSeeder');
         $this->call('StatusSeeder');
         $this->call('EmployerAdminSeeder');

         // Optional
        $this->call('PlaceFakerSeeder');
        $this->call('EmployerFakerSeeder');
        $this->call('RenterFakerSeeder');
        $this->call('ClientFakerSeeder');
        $this->call('BookingFakerSeeder');
    }
}
