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
            'preliminary',  // Предварительно
            'confirmed',    // Подтверждена
            'completed',    // Состоялась
            'cancelled',    // Отменена
            'online',       // Онлайн
            'mobile',       // Моб. приложение
        ];

        foreach ($aliases as $alias) {
            $status = new Status();

            $status->fill([
                'alias' => $alias,
            ])->save();
        }
    }
}
