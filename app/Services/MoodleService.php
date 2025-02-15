<?php

namespace App\Services;

use GuzzleHttp\Client;

/**
 * Class MoodleService.
 */
class MoodleService
{

    protected function call_moodle_api(string $function_name, array $params)
    {
        $base_url = config('sync.moodle.url');
        $token = config('sync.moodle.token');

        //add more params to the array
        $params['moodlewsrestformat'] = 'json';
        $params['wstoken'] = $token;
        $params['wsfunction'] = $function_name;


        $client = new Client();
        $response = $client->get($base_url, ['query' => $params]);

        print_r($params);


        return  json_decode($response->getBody()->getContents());


    }




    public function create_categories(array $categories, int|string $parent, bool $college = false)
    {
        $params = [];

        $id_key = $college ? 'college_id' : 'department_id';
        $name_key= $college ? 'college_name' : 'department_name';


        foreach ($categories as $index => $category) {



            //skip exists categories by idnumber






            $idnumber = $college ? get_college_idnumber($category->$id_key) : get_department_idnumber($category->$id_key);
            $existing_category = \App\Models\MoodleCategory::find2($idnumber);
            if ($existing_category && $existing_category->exists()) {
                continue;
            }


            $params["categories[{$index}][name]"] = $category->$name_key;
            $params["categories[{$index}][parent]"] = $parent ?? config('sync.moodle.categories.active_parent');
            $params["categories[{$index}][idnumber]"] = $idnumber;
        }

        $response =  $this->call_moodle_api('core_course_create_categories', $params);
        return $response;

    }


    public function create_courses($courses, int $category_id)
    {
        $function_name = 'core_course_create_courses';
        $params = [];
        $semester = $courses[0]->semester_id;

        $start_date = AdregService::semester_start_date($semester);
        $end_date = AdregService::semester_end_date($semester);

        $end_date = strtotime("+".config('sync.moodle.courses.ends_after')." months", $end_date);


        foreach ($courses as $index => $course) {

            $category_idnumber = get_department_idnumber($course->department_id);

            $existing_course = \App\Models\MoodleCourse::find2($course->section_id);

            if ($existing_course && $existing_course->exists()) {
                continue;
            }


            $params["courses[{$index}][fullname]"] = AdregService::section_name($course);
            $params["courses[{$index}][shortname]"] = AdregService::section_short_name($course);
            $params["courses[{$index}][categoryid]"] = $category_id;
            $params["courses[{$index}][idnumber]"] = $course->section_id;
            $params["courses[{$index}][summary]"] = $course->course_title;
            $params["courses[{$index}][format]"] = config('sync.moodle.courses.format');
            $params["courses[{$index}][summaryformat"] = 0;
            $params["courses[{$index}][startdate]"] = $start_date;
            $params["courses[{$index}][enddate]"] = $course->enddate;
            $params["courses[{$index}][showgrades]"] = 1;
            $params["courses[{$index}][newsitems]"] = 5;
            $params["courses[{$index}][numsections]"] = 7;
            $params["courses[{$index}][maxbytes]"] = 0;
            $params["courses[{$index}][showreports]"] = 1;
            $params["courses[{$index}][visible]"] = 1;
            #$params["courses[{$index}][hiddensections]"] = 0;
            $params["courses[{$index}][groupmode]"] = 0;
            $params["courses[{$index}][groupmodeforce]"] = 0;
            $params["courses[{$index}][defaultgroupingid]"] = 0;
            $params["courses[{$index}][enablecompletion]"] = 1;
            $params["courses[{$index}][completionnotify]"] = 0;
            #$params["courses[{$index}][lang]"] = 'en';
            #$params["courses[{$index}][forcetheme]"] = '';
            #$params["courses[{$index}][courseformatoptions][0][name]"] = '';
            #$params["courses[{$index}][courseformatoptions][0][value]"] = '';

        }

        return  $this->call_moodle_api($function_name, $params);



    }







}
