<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Speaker;
use App\Models\CoursesSpeaker;

use Illuminate\Support\Facades\DB;


class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Speaker::truncate();
        Topic::truncate();
        Course::truncate();
        CoursesSpeaker::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');


        $topics = Topic::factory(5)->create();

        $courses = Course::factory(20)->create([
            'topic_id' => $topics->random()->id,
        ]);
        $speakers = Speaker::factory(10)->create();

        foreach ($courses as $course) {
            $randomSpeakers = $speakers->random(rand(1, 5));
            $course->speakers()->attach($randomSpeakers);
        }

    }
}
