<?php

namespace App;

/**
 * CUrl
 */

class ExtractProgramData
{

    private $url = "https://port.hu/?token=91e635f95c7506696362ce9a19cb1fd5";

    private $extracted_extracted_programs = [];

    public function requestDOM($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $res = curl_exec($ch);

        curl_close($ch);

        return $res;
    }


    public function extractData()
    {
        $res = $this->requestDOM($this->url);

        $dom = new \DomDocument();
        @$dom->loadHTML($res);

        $xpath = new \DOMXpath($dom);
        $channels = $xpath->query("//div[@class='channel']//a/img");
        $programmes = $xpath->query("//ul[@class='channel-events']//li[1]");

        for ($i = 0; $i < 5; $i++) {
            $program = [];
            $program['channel_name'] = '"' . $channels->item($i)->getAttribute('alt') . '"';
            $program['program_start_date'] = '"' . $programmes->item($i)->childNodes->item(1)->textContent . '"';
            $program['program_title'] = '"' . $programmes->item($i)->childNodes->item(5)->textContent . '"';
            $program['program_description'] = '"' . $programmes->item($i)->childNodes->item(7)->textContent . '"';
            $this->extracted_extracted_programs[] = $program;
        }
    }

    public function getExtractedPrograms()
    {

        return $this->extracted_extracted_programs;
    }
}
