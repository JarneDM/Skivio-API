<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tasks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('tasks')->insert([
            [
                'title' => 'Design Homepage',
                'description' => 'Create a modern and responsive homepage design.',
                'status_id' => 1,
                'project_id' => 1,
                'assigned_to' => 1,
                'position' => 1,
                'due_date' => '2025-12-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Develop API',
                'description' => 'Build RESTful API for the application.',
                'status_id' => 2,
                'project_id' => 1,
                'assigned_to' => 3,
                'position' => 2,
                'due_date' => '2025-12-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Testing',
                'description' => 'Perform unit and integration testing.',
                'status_id' => 3,
                'project_id' => 1,
                'assigned_to' => 4,
                'position' => 3,
                'due_date' => '2025-12-25',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
