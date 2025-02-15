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

//        dd($response->getBody()->getContents());

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

        return $this->call_adreg_api('section', ['semester_id' => $semester_id, 'department_id' => $department_id]);
    }


    public static function all_colleges()
    {
        $as = new AdregService();
        return $as->colleges();
    }

    public static function semester_start_date(int $semester_id)
    {
        $as = new AdregService();
        $semester = $as->call_adreg_api('semester', ['semester_id' => $semester_id]);
        return strtotime($semester->semester_start_date);
    }

    public static function semester_end_date(int $semester_id)
    {
        $as = new AdregService();
        $semester = $as->call_adreg_api('semester', ['semester_id' => $semester_id])[0];
        return strtotime($semester->semester_end_date);
    }



    public static function section_short_name($section)
    {

        //ITCS 101-25-21


        $course_code = $section->course_code;
        $short_year = substr(explode('/', $section->semester_year)[1], -2);
        $semester_no = $section->semester_name == "First" ? 1 : ($section->semester_name == "Second" ? 2 : 3);
        $section_no = $section->section_no;
        return "{$course_code}-{$short_year}-{$semester_no}-{$section_no}";

    }

    public static function section_name($section)
    {
        //eg : ITCS 101-Sec1-Sem2-2025
        $course_code = $section->course_code;
        $section_no = $section->section_no;
        $semester_no = $section->semester_name == "First" ? 1 : ($section->semester_name == "Second" ? 2 : 3);
        $full_year = explode('/', $section->semester_year)[1];
        return "{$course_code}-Sec{$section_no}-Sem{$semester_no}-{$full_year}";
    }

}
