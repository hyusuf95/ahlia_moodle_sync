<?php

namespace App\Console\Commands;

use App\Services\AdregService;
use App\Services\MoodleService;
use Illuminate\Console\Command;

class CreateCoursesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:courses {semester} {--department=}';

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


        //if semester not found, return error
        $semester = (int) $this->argument('semester');
        $department = (int) $this->option('department');




        $adreg = new AdregService();
        $moodle = new MoodleService();



        if ($department >0) {

            $department_idnumber = get_department_idnumber($department);

            $department_id = \App\Models\MoodleCategory::find2($department_idnumber)->id;
            $sections = $adreg->sections($semester, $department);
            $this->info("Syncing " . count($sections) . " sections for {$department_idnumber}");
            $moodle->create_courses($sections, $department_id);


        }
        else

        {
            $departments = $adreg->departments();

            foreach ($departments as $department) {
                $department_idnumber = get_department_idnumber($department->department_id);


                $department_id = \App\Models\MoodleCategory::find2($department_idnumber)->id;
                $sections = $adreg->sections($semester, $department->department_id);
                $this->info("Syncing " . count($sections) . " sections for {$department->department_name}");
                $moodle->create_courses($sections, $department_id);
            }
        }








    }
}
