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



        return  json_decode($response->getBody()->getContents());


    }

    public function get_categories($params = [])
    {
        return $this->call_moodle_api('core_course_get_categories', $params);
    }

    public function get_category_by_idnumber($idnumber)
    {
        $params = [
            'criteria[0][key]' => 'idnumber',
            'criteria[0][value]' => $idnumber,
        ];
        $category = $this->get_categories($params);

        return $category[0] ?? null;


    }



    public function create_categories(array $categories, int|string $parent, bool $college = false)
    {
        $params = [];

        $id_key = $college ? 'college_id' : 'department_id';
        $name_key= $college ? 'college_name' : 'department_name';

        foreach ($categories as $index => $category) {

            $idnumber = $college ? "college_{$category->$id_key}" : "department_{$category->$id_key}";


            $params["categories[{$index}][name]"] = $category->$name_key;
            $params["categories[{$index}][parent]"] = $parent ?? config('sync.moodle.categories.active_parent');
            $params["categories[{$index}][idnumber]"] = $idnumber;
        }

        $response =  $this->call_moodle_api('core_course_create_categories', $params);
        return $response;

    }











    public function get_cat_id_by_idnumber($idnumber)
    {
        $category = $this->get_category_by_idnumber($idnumber);
        return $category ? (int) ($category->id) : -1;
    }





}
