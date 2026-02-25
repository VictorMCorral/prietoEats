<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offers')->insert([
            "date_delivery" => "2027-01-25",
            "time_delivery" => "14:00:05",
            "datetime_limit" => "2027-01-24 14:50:50",
        ]);

        DB::table('offers')->insert([
            "date_delivery" => "2027-02-10",
            "time_delivery" => "13:30:00",
            "datetime_limit" => "2027-02-09 13:20:00",
        ]);

        DB::table('offers')->insert([
            "date_delivery" => "2027-03-15",
            "time_delivery" => "12:15:30",
            "datetime_limit" => "2027-03-14 12:00:00",
        ]);
    }
}
