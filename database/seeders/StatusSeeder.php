<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('statuses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('statuses')->insert([
            [
                'name' => 'Backlog',
                'color' => '#6b7280',
                'position' => 1,
                'project_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'To-Do',
                'color' => '#3b82f6',
                'position' => 2,
                'project_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'In Progress',
                'color' => '#f59e0b',
                'position' => 3,
                'project_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Done',
                'color' => '#10b981',
                'position' => 4,
                'project_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
]);

    }
}
