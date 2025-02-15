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

            $is_exists = \App\Models\MoodleCategory::find2($category->$id_key)->exists();

            if ($is_exists) {
                continue;
            }

            $idnumber = $college ? "college_{$category->$id_key}" : "department_{$category->$id_key}";


            $params["categories[{$index}][name]"] = $category->$name_key;
            $params["categories[{$index}][parent]"] = $parent ?? config('sync.moodle.categories.active_parent');
            $params["categories[{$index}][idnumber]"] = $idnumber;
        }

        $response =  $this->call_moodle_api('core_course_create_categories', $params);
        return $response;

    }


    public function create_courses($courses)
    {
        $function_name = 'core_course_create_courses';
        $params = [];


        foreach ($courses as $index => $course) {
            $params["courses[{$index}][fullname]"] = AdregService::section_name($course);
            $params["courses[{$index}][shortname]"] = AdregService::section_short_name($course);
            $params["courses[{$index}][categoryid]"] = $course->categoryid;
            $params["courses[{$index}][idnumber]"] = $course->idnumber;
            $params["courses[{$index}][summary]"] = $course->summary;
            $params["courses[{$index}][format]"] = $course->format;
            $params["courses[{$index}][startdate]"] = $course->startdate;
            $params["courses[{$index}][enddate]"] = $course->enddate;
        }

    }








}
