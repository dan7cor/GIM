<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of newmodule
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_newmodule
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace newmodule with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/database_connect.php');

$context = context_system::instance();
$PAGE->set_context($context);
$name = "Calculadora";



// Check that user is logued in the course.
require_login();
// Page navigation and URL settings.
$PAGE->set_url('/mod/widget/view.php', array('filter'=>$name));
$PAGE->set_pagelayout('incourse');
$PAGE->set_title('Calculadora');




$final_grade=$_GET['expected'];

$query_test_names = "SELECT itemname FROM mdl_grade_items WHERE (id>1)";
$query_test_grade = "SELECT finalgrade FROM mdl_grade_grades WHERE (itemid>1) AND userid=".$USER->id;

$response = @mysqli_query($dbc,$query_test_names);
$response2 = @mysqli_query($dbc,$query_test_names);
$response3 = @mysqli_query($dbc,$query_test_grade);
if($response2){
   $array = [];
    while($row2 = mysqli_fetch_array($response2) and $row3 = mysqli_fetch_array($response3)){ 
        $name=$row2['itemname'];
        $value=$row3['finalgrade'];
        $name=str_ireplace(" ","",$name);
        $array[$name]=$value;

    }

}





echo $OUTPUT->header();
echo $OUTPUT->heading('Grade Calculator');

if($response){
    echo ("<form action='view2.php'>
        <fieldset>
            <legend>Tests:</legend>
                <input type='hidden' name='id' value=".$USER->id."><br>");

    while($row = mysqli_fetch_array($response)){
        $name=$row['itemname'];
       
        $name_var=str_ireplace(" ","",$name);
        if(!isset($array[$name_var])){
            $array[$name_var]=$final_grade;
        }
        else{
            
        }
        echo ($name.":<br>");
        echo ("<input type='number' value=".$array[$name_var]." max='70'><br>");
      
    }
    echo("      <h4>Nota Final:</h4><br>
                <input type='text' value=".$final_grade."><br>
            </fieldset>
        </form>");

}
/*

if((isset($_GET['test1']))&&(isset($_GET['test2']))&&(isset($_GET['test3']))){
    $t1=$_GET['test1'];
    $t2=$_GET['test2'];
    $t3=$_GET['test3'];
    $fg=($t1+$t2+$t3)/3;
}


echo ("<form action='view2.php'>
  <fieldset>
    <legend>Personal information:</legend>
    <input type='hidden' name='id' value=".$id."><br>
    Prueba 1:<br>
    <input type='text' name='test1' value=".$t1."><br>
    Prueba 2:<br>
    <input type='text' name='test2' value=".$t2."><br>
    Prueba 3:<br>
    <input type='text' name='test3' value=".$t3."><br><br>
    <
  </fieldset>
</form>");*/

//echo ("<br><a href='view.php?var=".$id."'>Volver a la calculadora</a>");

// Finish the page.
echo $OUTPUT->footer();
