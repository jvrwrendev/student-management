<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['subject_code' => 'IT-101', 'subject_name' => 'Introduction to Computing', 'description' => 'Foundations of computing and information technology.', 'units' => 3.0],
            ['subject_code' => 'IT-102', 'subject_name' => 'Computer Programming 1', 'description' => 'Structured programming using a general-purpose language.', 'units' => 3.0],
            ['subject_code' => 'CS-201', 'subject_name' => 'Data Structures and Algorithms', 'description' => 'Abstract data types and algorithm design.', 'units' => 3.0],
            ['subject_code' => 'CS-202', 'subject_name' => 'Discrete Mathematics', 'description' => 'Mathematical structures used in computing.', 'units' => 3.0],
            ['subject_code' => 'IT-203', 'subject_name' => 'Database Systems', 'description' => 'Design and management of relational databases.', 'units' => 3.0],
            ['subject_code' => 'BA-110', 'subject_name' => 'Principles of Management', 'description' => 'Core management theories and practices.', 'units' => 3.0],
            ['subject_code' => 'MA-101', 'subject_name' => 'College Algebra', 'description' => 'Algebraic foundations for higher mathematics.', 'units' => 3.0],
            ['subject_code' => 'EN-111', 'subject_name' => 'Purposive Communication', 'description' => 'Academic and professional communication skills.', 'units' => 3.0],
        ])->each(fn (array $subject): Subject => Subject::create($subject));
    }
}
