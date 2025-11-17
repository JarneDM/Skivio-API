<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('projects')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('projects')->insert([
            [
                'name' => 'My Project',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Website Redesign',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
