<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['first_name' => 'Maria', 'middle_name' => 'Santos', 'last_name' => 'Dela Cruz', 'department' => 'Computer Studies'],
            ['first_name' => 'John', 'middle_name' => 'Michael', 'last_name' => 'Reyes', 'department' => 'Computer Studies'],
            ['first_name' => 'Angela', 'middle_name' => null, 'last_name' => 'Bautista', 'department' => 'Mathematics'],
            ['first_name' => 'David', 'middle_name' => 'Lee', 'last_name' => 'Garcia', 'department' => 'Business'],
            ['first_name' => 'Sofia', 'middle_name' => 'Marie', 'last_name' => 'Flores', 'department' => 'Computer Studies'],
            ['first_name' => 'Robert', 'middle_name' => null, 'last_name' => 'Mendoza', 'department' => 'English'],
            ['first_name' => 'Grace', 'middle_name' => 'Ann', 'last_name' => 'Villanueva', 'department' => 'Mathematics'],
            ['first_name' => 'Paulo', 'middle_name' => 'Miguel', 'last_name' => 'Torres', 'department' => 'Business'],
        ])->each(fn (array $teacher): Teacher => Teacher::factory()->withUser()->create($teacher));
    }
}
