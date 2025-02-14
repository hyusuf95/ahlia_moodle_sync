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

        $this->syncColleges($college_id);

    }



    protected function syncColleges(?int $college_id = null)
    {
        $as = new AdregService();
        $colleges = $as->colleges($college_id);



        $ms = new MoodleService();
        $ms->create_categories(categories: $colleges, parent: config('sync.moodle.categories.active_parent'), college: true);
    }
}
