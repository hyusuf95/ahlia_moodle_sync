<?php

namespace App\Console\Commands;

use App\Models\MoodleCourse;
use App\Services\AdregService;
use Illuminate\Console\Command;

class ImportCoursesContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:courses-content {--semester=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $semester_id =  (int) ($this->option('semester'));

        $adreg = new AdregService();
        $courses = $adreg->sections($semester_id);

        foreach ($courses as $course) {
            $idnumber = $course->section_id;
            $id = MoodleCourse::find2($idnumber)->id;

            $this->info("The Course with id $id has idnumber $idnumber");
        }
    }
}
