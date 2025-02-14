<?php

namespace App\Services;
use GuzzleHttp\Client;

/**
 * Class AdregService.
 */
class AdregService
{


    public function call_adreg_api(string $function_name, array $params)
    {
        $base_url = config('sync.adreg.url');

        $url = "{$base_url}/{$function_name}";


        $client = new Client(['verify' => false]);
        $response = $client->get($url, ['query' => $params]);

        dd($response->getBody()->getContents());

        return json_decode($response->getBody()->getContents());

    }

    public function colleges(?int $college_id = null)
    {
        return $this->call_adreg_api('college', ['college_id' => $college_id]);
    }

    public function departments(?int $college_id = null)
    {
        return $this->call_adreg_api('department', ['college_id' => $college_id]);
    }

    public function sections(int $semester_id, ?int $department_id = null)
    {

        $this->call_adreg_api('section', ['semester_id' => $semester_id, 'department_id' => $department_id]);
    }


    public static function all_colleges()
    {
        $as = new AdregService();
        return $as->colleges();
    }

}
