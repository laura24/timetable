<?php
global $CFG;
require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');

require_once($CFG->dirroot.'/mod/timetable/config.php');
//require_once('../../lib/ddllib.php');
//require_once('form.php');

require_once('locallib.php');
global $OUTPUT, $CFG;
//echo $OUTPUT->header();
class smth extends moodleform{
	
	function definition() {

		global $COURSE;
		global $DB;
		$mform =& $this->_form;
		$groups = $DB->get_records('course_categories',null,'path ASC');
		/// Build groups and subgroups hierarchy for select
		foreach($groups as $group) 
		{
			if($group->parent == 0) 
			{
				$hierarchy[$group->id] = $group->name;
			}
			
		}
		asort($hierarchy);

		$mform->addElement('select', 'class', "selectie" , $hierarchy);
		$mform->addRule('class', null, 'required', null, 'client');
		$mform->setDefault('class',$this->_customdata['class']);
		$this->add_action_buttons();
	}


}

function our_function($class_id){

	global $USER;
	global $DB;
	global $IDS;

	$db_for_categories = $DB->get_records('course_categories');
	$db_for_courses = $DB->get_records('course');

	/* Find selected courses using hierarchical tree*/ 
	foreach($db_for_categories as $category)
	{	
		if( $category->parent == $class_id)
		{
			//echo $category->id;
			//echo "</br>";
			our_function($category->id);
		}		
	}	
	//-----------------------------------------------------------------------
	foreach($db_for_courses as $class)
	{	
		if( $class->category == $class_id)
		{
			
			$IDS[] = $class->id;


			echo '<h6>' . $class->shortname . '</h6> <br>';//Show courses
		}
	}
	//---------------------------------------------------------------------
}

global $IDS;
global $TIMETABLE_ID;

$form = new smth();

if ($form->is_submitted()) 
{
	$fromform = $form->get_data();
	$var = $fromform->class;
	our_function($var);
		
}

foreach($IDS as $k) 
{
	if($rec =  $DB->get_record('timetable', array('course'=>$k) ) )
		$TIMETABLE_ID[] = $rec->id;

}
print_r($TIMETABLE_ID);

timetable_display_passed_timetable($TIMETABLE_ID);


$form->display();
//echo $OUTPUT->footer();


