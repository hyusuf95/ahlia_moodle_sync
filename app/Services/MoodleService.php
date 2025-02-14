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


        return json_decode($response->getBody()->getContents());

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



    public function create_categories($categories = [], bool $college = false)
    {
        $params = [];

        $id_key = $college ? 'college_id' : 'department_id';
        $name_key= $college ? 'college_name' : 'department_name';

        foreach ($categories as $index => $category) {
            $params["categories[{$index}][name]"] = $category[$name_key];
            $params["categories[{$index}][parent]"] = $category['parent'] ?? 0;
            $params["categories[{$index}][idnumber]"] = $category[$id_key];
            $params["categories[{$index}][description]"] = $category['description'] ?? '';
        }
        return $this->call_moodle_api('core_course_create_categories', $params);

    }





}
