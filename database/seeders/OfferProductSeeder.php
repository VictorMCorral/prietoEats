<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfferProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_offers')->insert([
            "offer_id" => 1,
            "product_id" => 1,
        ]);

        DB::table('product_offers')->insert([
            "offer_id" => 1,
            "product_id" => 2,
        ]);

        DB::table('product_offers')->insert([
            "offer_id" => 2,
            "product_id" => 3,
        ]);

        DB::table('product_offers')->insert([
            "offer_id" => 2,
            "product_id" => 4,
        ]);

        DB::table('product_offers')->insert([
            "offer_id" => 3,
            "product_id" => 5,
        ]);


    }
}
