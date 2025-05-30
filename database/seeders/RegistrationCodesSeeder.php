<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationCodesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('registration_codes')->updateOrInsert(
            ['code' => 'PRA13'],
            ['role' => 'présentateur', 'created_at' => now(), 'updated_at' => now()]
        );

        DB::table('registration_codes')->updateOrInsert(
            ['code' => 'SRA13'],
            ['role' => 'secretaire', 'created_at' => now(), 'updated_at' => now()]
        );
    }
}