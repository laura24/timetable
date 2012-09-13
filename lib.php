<?php
// placeholder

global $CFG;
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/mod/timetable/locallib.php');

function timetable_install() {
    // do nothing, just a placeholder
}


function timetable_add_instance($timetable) {
    /**
     * Adds a new timetable to a course
     * REQUIRED function
     * @param  timetable an object constructed by the module creation form
     * @return           the id of the new record
     */
	 print_r($timetable);
    global $TIMETABLE_MODIFIER,$DB;

    $tt = new object();   // default entry

    $tt->name = get_string('modulename', 'timetable');
    $tt->course = $timetable->course;
    $timetable->id = $DB->insert_record("timetable", $tt);

    timetable_insert_fields($timetable);
    
    timetable_cron();

    return $timetable->id; // return id
}





function timetable_update_instance($timetable) {
	global $DB;
    /**
     * Updates an already existent module instance
     * @param timetable an object created by the module creation form
     * @return id of the containing course
     */
    // delete the old instance and then just add a new instance
    $course_id = $timetable->course;
    $module_id = $DB->get_record('timetable', array('course'=>$course_id))->id;

	echo "HELLO";
	echo $module_id;
	
    $DB->delete_records('timetable_base', array('timetable'=>$module_id ));
	
    $timetable->id = $module_id/*->id*/;
	echo "BUNA";
	$var =  $timetable->id;
	echo  $var;
    timetable_insert_fields($timetable);

    // don't wait for cron
    timetable_cron();
    
    // force the cast because PHP thinks the function is returning
    // a string
	
    return (integer)$course_id;
}


function timetable_delete_instance($id) {
    /**
     * Deletes a timetable instance.
     * @param  id the id of the module to be deleted
     * @return    true if no errors occurred, false otherwise
     */

	 global $DB;
    $DB->delete_records('timetable_base', array('timetable'=>$id));
    $DB->delete_records('timetable', array('id'=>$id));

    timetable_cron();
    
    return true;
}



function timetable_user_outline($course, $user, $mod, $timetable) {
    /**
     * Function not implemented in Timetable 0.1.0
     */
}


function timetable_user_complete() {
    /**
     * Function not implemented in Timetable 0.1.0
     */
}



function timetable_get_view_actions() {
    /**
     * Function not implemented in Timetable 0.1.0
     */
}


function timetable_set_view_actions() {
    /**
     * Function not implemented in Timetable 0.1.0
     */
}


function timetable_cron() {
    /**
     * Updates the cached HTML timetable
     * @return  nothing
     */
    global $TIMETABLE_HTML_FILE;
    $filehandler = fopen($TIMETABLE_HTML_FILE, 'w');
    
    fwrite($filehandler, timetable_display());
    
    fclose($filehandler);
    
}


function timetable_build_course_url($course) {
    global $CFG;
    return $CFG->wwwroot.'/course/view.php?id='.$course->id;
}


?>
