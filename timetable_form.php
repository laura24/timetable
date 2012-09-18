<?php
global $CFG;
require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');

require_once($CFG->dirroot.'/mod/timetable/config.php');
require_once($CFG->dirroot.'/mod/timetable/mod_form.php');

require_once('locallib.php');
global $OUTPUT, $CFG;


$context = get_context_instance(CONTEXT_SYSTEM);
$PAGE->set_url('/timetable_form.php');

$PAGE->set_pagelayout('frontpage');
$PAGE->set_context($context);
$PAGE->set_title(format_string("Timetable History"));
$PAGE->set_heading(format_string("Timetable History"));


echo $OUTPUT->header();

echo "<h3 align='center'> Timetable History </h3> <br/> <br/>";

global $IDS;
global $TIMETABLE_ID;

$form = new smth();

if ($form->is_submitted()) 
{
	$fromform = $form->get_data();
	$var = $fromform->class;
	our_function($var);

    foreach($IDS as $k) 
    {
	   if($rec =  $DB->get_record('timetable', array('course'=>$k) ) )
	   	$TIMETABLE_ID[] = $rec->id;
    }
   echo timetable_display_passed_timetable($TIMETABLE_ID);
  //  print_r($TIMETABLE_ID);
  //  redirect(new moodle_url('/mod/timetable/timetable_index.php'));
    
}


//$form = new smth();
if(!$TIMETABLE_ID)
$form->display();

echo $OUTPUT->footer();


