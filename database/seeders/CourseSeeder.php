<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'course_code' => 'BSIT',
                'course_name' => 'Bachelor of Science in Information Technology',
                'description' => 'Focuses on the application of computer technology to meet the everyday needs of organizations.',
            ],
            [
                'course_code' => 'BSCS',
                'course_name' => 'Bachelor of Science in Computer Science',
                'description' => 'Emphasizes the theoretical foundations of computing and software development.',
            ],
            [
                'course_code' => 'BSBA',
                'course_name' => 'Bachelor of Science in Business Administration',
                'description' => 'Covers management, marketing, finance, and operations fundamentals.',
            ],
            [
                'course_code' => 'BSED',
                'course_name' => 'Bachelor of Science in Secondary Education',
                'description' => 'Prepares students to teach in high school and preparatory education levels.',
            ],
            [
                'course_code' => 'BSA',
                'course_name' => 'Bachelor of Science in Accountancy',
                'description' => 'Trains students in accounting, auditing, and taxation practices.',
            ],
            [
                'course_code' => 'BSCE',
                'course_name' => 'Bachelor of Science in Civil Engineering',
                'description' => 'Covers the design, construction, and maintenance of the built environment.',
            ],
        ])->each(fn (array $course): Course => Course::create($course));
    }
}
