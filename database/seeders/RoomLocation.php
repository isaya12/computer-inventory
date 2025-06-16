<?php

namespace Database\Seeders;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomLocation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                DB::table('locations')->insert([
                    'name' => 'Lab 1',
                    'block' => 'A',
                    'floor' => '4',
                ]);

                DB::table('locations')->insert([
                    'name' => 'Lab 1',
                    'block' => 'A',
                    'floor' => '4',
                ]);

                DB::table('locations')->insert([
                    'name' => '05',
                    'block' => 'D',
                    'floor' => 'Ground',
                ]);
    }
}
