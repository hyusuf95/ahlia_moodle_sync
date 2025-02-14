<?php

namespace App\Console\Commands;

use App\Services\AdregService;
use App\Services\MoodleService;
use Illuminate\Console\Command;

class SyncCategoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:categories {college?}';

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
        $college_id = (int) $this->argument('college');

        if ($college_id) {
            $this->syncColleges($college_id);
            $this->syncDepartments($college_id);
        }
        else
        {
            $all_colleges = AdregService::all_colleges();
            foreach ($all_colleges as $college)
            {
                $this->info("Syncing college {$college->college_name}");
                $this->syncColleges($college->college_id);

                $this->info("Syncing departments for college {$college->college_name}");
                $this->syncDepartments($college->college_id);
            }
        }
    }



    protected function syncColleges(?int $college_id = null)
    {
        $as = new AdregService();
        $colleges = $as->colleges($college_id);



        $ms = new MoodleService();
        $ms->create_categories(categories: $colleges, parent: config('sync.moodle.categories.active_parent'), college: true);
    }


    protected function syncDepartments(int $college_id)
    {
        $as = new AdregService();
        $departments = $as->departments($college_id);

        $college_idnumber = "college_$college_id";

        $ms = new MoodleService();
        $parent_id = $ms->get_cat_id_by_idnumber($college_idnumber);
        $this->info("thie parent id is $parent_id");
        $ms->create_categories(categories: $departments, parent: $parent_id, college: false);

    }

}
