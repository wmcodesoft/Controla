<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['code' => 'PORT-01', 'name' => 'Portería Principal', 'address' => 'Av. Principal #1-1', 'type' => 'porteria'],
            ['code' => 'PORT-02', 'name' => 'Portería Secundaria', 'address' => 'Calle 2 #3-4', 'type' => 'porteria'],
            ['code' => 'SEDE-01', 'name' => 'Edificio Corporativo', 'address' => 'Cra 5 #6-7', 'type' => 'edificio'],
            ['code' => 'BOD-01', 'name' => 'Bodega Principal', 'address' => 'Zona Industrial Km 2', 'type' => 'bodega'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(['code' => $location['code']], $location);
        }
    }
}
