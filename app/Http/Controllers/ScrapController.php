<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;
use KubAT\PhpSimple\HtmlDomParser;

class ScrapController extends Controller {

    private $url = 'https://search.studyinaustralia.gov.au/course/search-results.html?qualificationid=12&subjectid=E&locationid=4';
    public $university = array(),$universityArray = array(),$degree=array(),$degreeArray = array(),$level=array(), $levelArray = array();

    public function index() {
      $data = $this -> scrap();
      
    }

    private function scrap() {

      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        curl_close($ch);


           $dom = HtmlDomParser::str_get_html($response);
        
           $this->university = $dom->find('h2.univ_tit > a');//find each university name list
           foreach($this -> university as $key =>  $u)
           {
               $this -> universityArray[] =$this -> university[$key]->innertext; // list of universitites in array
                
           }
           
           $this -> degree = $dom -> find("h3.crs_tit > a");  // finding courses inside each university
           foreach ($this -> degree as $key => $d){
               $this -> degreeArray[] =$d->innertext;    // list of courses in array
                
           }
           
           $this -> level = $dom -> find("div.fl_w100 span");
           foreach($this -> level as $key =>$detail){
                $this -> levelArray[] =  $detail -> plaintext; //list of  details of course 
            }

            $this->university = $dom->find('div.sr_p'); 


            $uniCount = count($dom->find('div.sr_p')); // count div.sr_p that holds university title

            $array_of_univ =  array();
            $array_of_degree= array();
            $array_of_level = array();
            $lpointer =0;// pointer for details for course eg.level, duration etc
            $cpointer=0; //pointer for course
            
            // create multidimestional array of universities, courses and details of course
            for ($i = 0; $i < $uniCount; $i++) { // loop to read university

                $array_of_univ[] = $this -> universityArray[$i];
                $courseCount = count($this->university[$i]->find('div.rs_cnt h3'));

                for ($j = 0; $j < $courseCount; $j++) { // loop to read course 
                    if(isset($this -> degreeArray[$cpointer])){ 
                            $array_of_degree[$i][]=$this -> degreeArray[$cpointer];
                           
                           
                        }
                   
                   $detailsCount= count($this->university[$i]->find('div.fl_w100 > span'), 0);
                   
                    $loop = 0; // counts details information of course and break loop

                    for($k=0;$k<=$detailsCount;$k++){ // loop to read course details
                        
                    if(isset($this -> levelArray[$lpointer])){
                        if($loop==5 || $loop==4 && $this -> levelArray[$lpointer]=="Undergraduate"){
                            break;
                        }
                            $array_of_level[$i][$j][] = $this -> levelArray[$lpointer];
                            $loop++;
                           
                        } 
                       $lpointer++; // pointer for details for course eg.level, duration etc
                    } // end of course detail loop
                    $cpointer++;//pointer for course
                    
                   /*************Create array of university and relavant courses with details information *********************/
                    $list[] = array(
                        
                        "University_Name" =>$array_of_univ[$i],
                        "Course_Name"=> $array_of_degree[$i][$j],
                        "Level_of_study"=>$array_of_level[$i][$j][0], 
                        "Start_Date" => $array_of_level[$i][$j][1], 
                        "Duration" => $array_of_level[$i][$j][2], 
                        "Tution_Fees" => $array_of_level[$i][$j][3], 
                        "Total_Fee" => isset($array_of_level[$i][$j][4])?$array_of_level[$i][$j][4]:'N/A' 
                    );
                } // end of course loop
            }// end of university loop

                     
            /*************Data Export in xls format block *********************/

                $fileName = "export_data" . rand(1,100) . ".xls";

                if ($list) {


                        // headers for download
                        header("Content-Disposition: attachment; filename=\"$fileName\"");
                        header("Content-Type: application/vnd.ms-excel");

                        $flag = false;
                        foreach($list as $row) {
                                if(!$flag) {
                                        // display column names as first row
                                        echo implode("\t", array_keys($row)) . "\n";
                                        $flag = true;
                                }
;
                                echo implode("\t", array_values($row)) . "\n";
                        }
                       
                        return $list;			
                }

      


    }
    



}

?>
