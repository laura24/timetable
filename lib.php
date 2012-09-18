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
    global $TIMETABLE_MODIFIER,$DB;

    $tt = new object();   // default entry
	
	$ok = check_valid($timetable);
	if($ok) {
		$tt->name = get_string('modulename', 'timetable');
		$tt->course = $timetable->course;
		$tt->timecreated = time();
		$timetable->id = $DB->insert_record("timetable", $tt);
		
		timetable_insert_fields($timetable);
		return $timetable->id;
	}else {
		echo "Eroare: Verificati suprapunerea cursurilor. Clasele nu se pot termina mai tarziu de ora 22";
		return NULL;
	}

}

function check_valid($timetable) {
	global $DB;
	$ok = 1;
	if(!isset($timetable->sess_dis_0)) {
		$rec = $DB->get_records('timetable_base',array('day'=>$timetable->sess_day_0));
		$hour0 = $timetable->sess_hour_0+8;
		$hour_end0 = $hour0 + $timetable->sess_len_0;
		foreach ($rec as $r) {
			if($r->active == 0)
				if($r->classroom == $timetable->classroom) {
					if(!($r->hour >= $hour_end0 || $r->hour_end <= $hour0))
						$ok = 0;
				}
		}
		if($hour_end0 > 22)
			$ok = 0;
	}
	
	if(!isset($timetable->sess_dis_1)) {
		$rec = $DB->get_records('timetable_base',array('day'=>$timetable->sess_day_1));
		$hour1 = $timetable->sess_hour_1+8;
		$hour_end1 = $hour1 + $timetable->sess_len_1;
		foreach ($rec as $r) {
			if($r->active == 0)
				if($r->classroom == $timetable->classroom) {
					if(!($r->hour >= $hour_end1 || $r->hour_end <= $hour1))
						$ok = 0;
				}
		}
		if( $hour_end1 > 22)
			$ok = 0;
	}
	
	if(!isset($timetable->sess_dis_2)) {
		$rec = $DB->get_records('timetable_base',array('day'=>$timetable->sess_day_2));
		$hour2 = $timetable->sess_hour_2+8;
		$hour_end2 = $hour2 + $timetable->sess_len_2;
		foreach ($rec as $r) {
			if($r->active == 0)
				if($r->classroom == $timetable->classroom) {
					if(!($r->hour >= $hour_end2 || $r->hour_end <= $hour2))
						$ok = 0;
				}
		}
		if( $hour_end2 > 22)
			$ok = 0;
	}
	
	if(!isset($timetable->sess_dis_0)) {
		if(!isset($timetable->sess_dis_1)) {
			if($timetable->sess_day_0 == $timetable->sess_day_1) {
				if(!($hour0 >= $hour_end1 || $hour_end0 <= $hour1)) {
					$ok = 0;
				}
			}
		}
		
		if(!isset($timetable->sess_dis_2)) {
			if($timetable->sess_day_0 == $timetable->sess_day_2) {
				if(!($hour0 >= $hour_end2 || $hour_end0 <= $hour2)) {
					$ok = 0;
				}
			}
		}
	}
	
	if(!isset($timetable->sess_dis_1)) {
		if(!isset($timetable->sess_dis_2)) {
			if($timetable->sess_day_1 == $timetable->sess_day_2) {
				if(!($hour1 >= $hour_end2 || $hour_end1 <= $hour2)) {
					$ok = 0;
				}
			}
		}
	}
			
	
	return $ok;
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
	$aux_data = $DB->get_records('timetable_base', array('timetable'=>$module_id ));

	$DB->delete_records('timetable_base', array('timetable'=>$module_id ));
	
	$ok = check_valid($timetable);
	if($ok) {
		$timetable->id = $module_id/*->id*/;
		
		$var =  $timetable->id;

		timetable_insert_fields($timetable);

	
		return (integer)$course_id;
	}else {
		foreach($aux_data as $data) {
			$DB->insert_record('timetable_base',$data);
		}
		echo "Eroare. Verificati daca : ati suprapus cursurile, clasele se termina mai tarziu de ora 22, nu ati facut nici o modificare asupra orarului.";
		return NULL;
	}
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


  //  timetable_cron();
  //   timetable_cron();

    
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

    $current_year = date('Y', time() ); 

    //automatically set timetable inactive on these dates;
    
    define("AUTUMN_SESSION", $current_year."-09-17");
    define("SPRING_SESSION", $current_year."-06-15");
    define("SUMMER_SESSION", $current_year."-08-15");
    $aux1 = strtotime(AUTUMN_SESSION );
    $aux2 = strtotime(SPRING_SESSION );
    $aux3 = strtotime(SUMMER_SESSION );


    if( (time() > $aux1 && time() < ($aux1+24*3600)) 
        || (time() > $aux2 && time() < ($aux2+24*3600)) 
            || (time() > $aux3 && time() < ($aux3+24*3600)) )
    {
            $rec = $DB->get_records('timetable_base');
             foreach ($rec as $r) 
             {
             timetable_update_active($r);
             }
    }

}


function timetable_build_course_url($course) {
    global $CFG;
    return $CFG->wwwroot.'/course/view.php?id='.$course->id;
}




function timetable_update_active($record){

    /**
     * Updates the field named "active"
     * @return  $record_id;
     */

    global $DB;

    $record->active=1;
    $record_id = $record->id;

    $DB->delete_records('timetable_base', array('id'=>$record_id));

    $DB->insert_record('timetable_base', $record);

    return (integer)$record_id;

}

?>
