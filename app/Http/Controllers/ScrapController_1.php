<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use KubAT\PhpSimple\HtmlDomParser;
use App\Exports\DataExport;

class ScrapController extends Controller {

    private $url = 'https://search.studyinaustralia.gov.au/course/search-results.html?qualificationid=12&subjectid=E&locationid=4';
    public $university = array(), $level, $start_date, $duration, $fees;
    public $universityArray = array(), $degreeArray = array(), $levelArray = array(), $start_dateArray = array(), $durationArray = array(), $feesArray = array(), $degree = array();

    public function index() {
        return Excel::download(new DataExport(), 'data-internet-providers.xlsx');
    }

    public function scrap() {


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        curl_close($ch);


        $dom = HtmlDomParser::str_get_html($response);
        $ucount = count($dom->find('div.sr_p'));
//
//            $this->university = $dom->find('h2.univ_tit');
//           foreach($this -> university as $key =>  $u)
//           {
////               $this -> universityArray[] =$this -> university[$key]->innertext;
//                
//           }
//           
//           $this -> degree = $dom -> find("h3.crs_tit");
//           foreach ($this -> degree as $key => $d){
////                $this -> degreeArray[] =$d->innertext;
//                
//           }
        echo "<pre>";
        $this->university = $dom->find('div.sr_pg'); //finding university class

        for ($i = 0; $i < $ucount; $i++) {
            echo $this->university[$i] ->innertext;
            $dCount = count($this->university[$i]->find('div.rs_cnt'));
            echo $dCount . "--";
            $this->universityArray[$i][] = $this->university[$i]->innertext;
            $this->degreeArray[] = $this->university[$i]->find('div.rs_cnt');

            if (count($this->degreeArray)) {
//                var_dump($this->university);
                echo "------";
            }die;
            for ($j = 0; $j <= 1; $j++) {
                $detailsCount = count($this->degreeArray[$j]->find("div.fl_w100 > span"));
                $this->universityArray[$i][$j][] = $this->degreeArray[$j]->innerText;
                $detail = $this->degreeArray[$j]->find(".fl_w100 > span");
                $this->universityArray[$i][$j][] = $detail[0]->innertext;
                $this->universityArray[$i][$j][] = $detail[1]->innertext;
                $this->universityArray[$i][$j][] = $detail[2]->innertext;
                $this->universityArray[$i][$j][] = $detail[3]->innertext;
            }
        }

        echo "<pre>";
        print_r($this->universityArray);
        die;


        $result_array = array(
            "zip" => $zip_code,
            "city" => $city_state,
            "fiber" => $array_of_precentage[0],
            "cable" => $array_of_precentage[1],
            "dsl" => $array_of_precentage[2],
            "wired" => $array_of_precentage[3],
            "providers" => $providers
        );

        return $result_array;
    }

}

?>
