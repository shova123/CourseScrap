<?php
 
namespace App;
 
use Illuminate\Database\Eloquent\Model;
 
class Courses extends Model
{

 
   protected $fillable = ['university_name','course_name','level_of_study','start_date','duration','fees','total_fees'];
}