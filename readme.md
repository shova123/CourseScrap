<h2>TO clone repository</h3> <br/>
 step1. git clone https://github.com/shova123/CourseScrap.git<br/>
 step2. composer update<br/>



1.Course craping get the data from site studyinaustralia.gov.au.<br/>

2.List of data from website is extracted under defined filter set <br/>
    Level of study -Bachelor Degree <br/>
    Field of study -computer science and IT <br/>
    Regions - Queensland  <br/>

3.Platform used Laravel <br/>

4.Curl is used to connect with site and  scrap data from site .Reference link is :https://search.studyinaustralia.gov.au/course/search-results.html?qualificationid=12&subjectid=E&locationid=4 and html dom parsser library is used to read and extract data from dom.
<br/>

5.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        curl_close($ch);

fetch and return dom data using curl <br/>

6. $dom = HtmlDomParser::str_get_html($response);  <br/>
This line parse the return data and helps in manipulating dom data.<br/>

7.$this->university = $dom->find('h2.univ_tit > a');//find each university name list<br/>
           foreach($this -> university as $key =>  $u)
           {
               $this -> universityArray[] =$this -> university[$key]->innertext; // list of universitites in array
                
           } 
<br/>
This block of code search university title in dom through its class name. This list all university name available in array universityArray.<br/>

8. $this -> degree = $dom -> find("h3.crs_tit > a");  // finding courses inside each university <br/>
           foreach ($this -> degree as $key => $d){
               $this -> degreeArray[] =$d->innertext;    // list of courses in array <br/>
                
           }
This block of code search level of study title in dom through its class name. This list all university name available in array degreeArray. <br/>
           
 9.$this -> level = $dom -> find("div.fl_w100 span");
           foreach($this -> level as $key =>$detail){
                $this -> levelArray[] =  $detail -> plaintext; //list of  details of course 
            } <br/>

This block of code search level of course information in dom through its class name. This list all university name available in array levelArray <br/>
 
Note: firstly all required data are fetched individually and set those data in data. <br/>

7. find count of university , course and course information available in dom. <br/>

8.Create standard array of format from  array of universities, course and course information <br/>

[0]=>
  array(8) { <br/>
    [0]=>
    string(15) "University Name" <br/>
    [1]=>
    string(11) "Course Name" <br/>
    [2]=>
    string(14) "Level of study" <br/>
    [3]=>
    string(10) "Start Date" <br/>
    [4]=>
    string(10) "Start Date" <br/>
    [5]=>
    string(8) "Duration" <br/>
    [6]=>
    string(11) "Tution Fees" <br/>
    [7]=>
    string(9) "Total Fee" <br/>
  }
  [1]=>
  array(19) { <br/>
    [0]=>
    array(7) { <br/>
      [0]=>
      string(100) " CQUniversity Australia " <br/>
      [1]=>
      string(123) " Bachelor of Digital Media - CC24 " <br/>
      [2]=>
      string(13) "Undergraduate" <br/>
      [3]=> 
      string(23) " March 2021, July 2021 " <br/>
      [4]=>
      string(7) "3 years" <br/>
      [5]=>
      string(15) "28,320 Per Year" <br/>
      [6]=>
      string(16) "Term fee: 14,160" <br/>
    }

9.export of data in xls format using header information <br/>

                        header("Content-Disposition: attachment; filename=\"$fileName\""); <br/>
                        header("Content-Type: application/vnd.ms-excel");
 <br/>

10. final output  in excel format is saved in public folder for sample reference  
