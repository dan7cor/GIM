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



//Here you make the different querys to the database
$query_test_names = "SELECT itemname,aggregationcoef2 FROM mdl_grade_items WHERE (id>1)";

$response = @mysqli_query($dbc,$query_test_names);



echo $OUTPUT->header();
echo $OUTPUT->heading('Grade Calculator');

//if the query is not NULL than you get the values of the grades on a form that is similar to the one where you asked for the grades
if($response){
    $cont=0;
    $nota_final=0;
    echo ("<form action='view2.php'>
        <fieldset>
            <legend>Tests:</legend>
                <input type='hidden' name='id' value=".$USER->id."><br>");
    while($row = mysqli_fetch_array($response)){
        $name=$row['itemname'];
        $weight=$row['aggregationcoef2'];
        $name_var=str_ireplace(" ","",$name);
        $grade=$_GET[$name_var]*$weight;

        //you add the grades that have their weight included to the final grade
        $nota_final+=$grade;
        echo ($name.":<br>");
        echo ("<input type='number' value=".$_GET[$name_var]." max='70'><br>");
        $cont++;
    }






    

    echo("      <h4>Nota Final:</h4><br>
                <input type='text' value=".$nota_final."><br>
            </fieldset>
        </form>");

}

echo $OUTPUT->footer();
