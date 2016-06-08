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

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... newmodule instance ID - it should be named as the first character of the module.

if(isset($_GET["var"])){
    $id=$_GET["var"];
}

if ($id) {
    $cm         = get_coursemodule_from_id('widget', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $widget  = $DB->get_record('widget', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $widget  = $DB->get_record('widget', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $widget->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('widget', $widget->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_widget\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $widget);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/widget/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($widget->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('newmodule-'.$somevar);
 */

//database call

$query_test_names = "SELECT itemname FROM mdl_grade_items WHERE (id>1)";
$query_test_grade = "SELECT finalgrade FROM mdl_grade_grades WHERE (itemid>1) AND userid=".$USER->id;

$response = @mysqli_query($dbc,$query_test_names);
$response2 = @mysqli_query($dbc,$query_test_names);
$response3 = @mysqli_query($dbc,$query_test_grade);

//The grades for every test is saved in the array in this if

if($response2){
   $array = [];
    while($row2 = mysqli_fetch_array($response2) and $row3 = mysqli_fetch_array($response3)){ 
        $name=$row2['itemname'];
        $value=$row3['finalgrade'];
        $name=str_ireplace(" ","",$name);
        $array[$name]=$value;
    }

}




// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($widget->intro) {
    echo $OUTPUT->box(format_module_intro('widget', $widget, $cm->id), 'generalbox mod_introbox', 'widgetintro');
}


echo $OUTPUT->heading('Grade Calculator');

$grades_flag=FALSE;

if($response){
    echo ("<form action='view2.php'>
        <fieldset>
            <legend>Tests:</legend>
                <input type='hidden' name='id' value=".$USER->id."><br>");
    while($row = mysqli_fetch_array($response)){

        $name=$row['itemname'];
        $name_get=str_ireplace(" ","",$name);
        if(!isset($array[$name_get])){
            $array[$name_get]="0";
            $grades_flag=TRUE;
        }
        echo ($name.":<br>");
        echo ("<input type='number' name=".$name_get." value='".$array[$name_get]."' max='70'><br>  ");
    }
    echo("      <input type='submit' value='Probar'>
            </fieldset>
        </form>");

}
if($grades_flag){
echo("<br>");
echo("<form action='view3.php'>     
         <h4>Nota Final:</h4><br>
                <input type='hidden' name='id' value=".$USER->id."><br>
                <input type='text' name='expected'><br>
                <input type='submit' value='Probar'>
            </fieldset>
        </form>");
}




    
/*echo("<form>
  Nota Final:<br>
  <input type='text' name='final' value=''><br>
</form>");*/

// Finish the page.
echo $OUTPUT->footer();
