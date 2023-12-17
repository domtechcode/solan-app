<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TasksTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('tasks')->insert([
      [
        'id' => 1,
        'text' => 'Project #1',
        'start_date' => '2023-12-05 13:00:00',
        'duration' => 5,
        'progress' => 0.8,
        'parent' => 0,
      ],
      [
        'id' => 2,
        'text' => 'Task #1',
        'start_date' => '2023-12-05 13:00:00',
        'duration' => 1,
        'progress' => 0.5,
        'parent' => 1,
      ],
      [
        'id' => 3,
        'text' => 'Task #2',
        'start_date' => '2023-12-05 14:00:00',
        'duration' => 2,
        'progress' => 0.7,
        'parent' => 1,
      ],
    ]);
  }
}
