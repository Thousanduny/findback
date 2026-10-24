<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Главный корпус',
            'Корпус А',
            'Корпус Б',
            'Библиотека',
            'Столовая',
            'Общежитие',
            'Улица / Территория кампуса'
        ];

        foreach ($locations as $location) {
            Location::create(['name' => $location]);
        }
    }
}
