<?php

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aliases = [
            'new',          // Новая
            'confirmed',    // Подтверждена
            'completed',    // Состоялась
            'cancelled',    // Отменена
        ];

        foreach ($aliases as $alias) {
            $status = new Status();

            $status->fill([
                'alias' => $alias,
            ])->save();
        }
    }
}
