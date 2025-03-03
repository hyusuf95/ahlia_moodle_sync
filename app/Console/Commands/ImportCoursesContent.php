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
            $moodle_course = MoodleCourse::find2($idnumber);
            $moodle_id = $moodle_course->id;
            $shortname = $moodle_course->shortname;

            $moodle_root = config('sync.moodle.source_root');
            $backup_folder = config('sync.moodle.courses.backup_folder');

            //find file that contains the shortname
            $find = "find $backup_folder -name '*$shortname*'";
            $backup_file = exec($find);

            dd($backup_file, $find);

            //if backup file found, restore it

            if ($backup_file) {
                $this->info("Restoring $shortname");
                $restore = "php $moodle_root/admin/cli/restore_backup.php --file=$backup_file --courseid=$moodle_id";
                dd($restore);
            } else {
                $this->error("Backup file not found for $shortname");
            }
        }
    }
}
