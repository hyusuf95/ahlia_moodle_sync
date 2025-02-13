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

        $url = "{$base_url}/webservice/rest/server.php?wstoken={$token}&wsfunction={$function_name}&moodlewsrestformat=json";

        foreach ($params as $key => $value) {
            $url .= "&{$key}={$value}";
        }

        $client = new Client();
        $response = $client->get($url);

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



    public function create_categories($categories = [], bool $check_exists = true)
    {
        $params = [];

        foreach ($categories as $index => $category) {
            $params["category[{$index}][name]"] = $category['college_name'];
            $params["category[{$index}][parent]"] = $category['parent'] ?? 0;
            $params["category[{$index}][idnumber]"] = $category['college_id'];
            $params["category[{$index}][description]"] = $category['description'] ?? '';
            $params["category[{$index}][visible]"] = 1;
        }
        return $this->call_moodle_api('core_course_create_categories', $params);

    }





}
