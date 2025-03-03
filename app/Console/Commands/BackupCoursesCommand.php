<?php

namespace App\Console\Commands;

use App\Models\MoodleCourse;
use App\Services\AdregService;
use Illuminate\Console\Command;

class BackupCoursesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:courses {--semester=}';

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
        //if semester not provided, ask for it
        $semester_id =  (int) ($this->option('semester'));

        $adreg = new AdregService();
        $courses = $adreg->sections($semester_id);

        $moodle_path = config('sync.moodle.source_root');
        $backup_folder = config('sync.moodle.courses.backup_folder');

        foreach ($courses as $course) {
            $idnumber = $course->section_id;
            $moodle_course = MoodleCourse::find2($idnumber);
            $moodle_id = $moodle_course->id;


            $this->info("If backup file exists in $backup_folder, skip this course");

            //read from backedup.txt file
            $backedup = file_get_contents("backedup.txt");
            //get first number before the dash

            $backed_up_id = explode('-', $backedup)[0];

            if ($backed_up_id == $moodle_id) {
                $this->info("Course $idnumber already backed up");
                continue;
            }


            $command = "php $moodle_path/admin/cli/backup.php --courseid=$moodle_id --destination=$backup_folder";

            $this->info("Backing up course $idnumber");
            exec($command);


            //transfering backup to remote server
            $remote_path = '/home/moodle/ssd/moodle.ahlia.edu.bh/moodle_courses_backup';
            $remote_command = "rsync -havz $backup_folder/ moodle_hetzner:$remote_path";

            $this->info("Transferring backup to remote server");
            exec($remote_command);

            //delete local backup
            $this->info("Deleting local backup");
            exec("rm -rf $backup_folder/*");


        }
    }
}
