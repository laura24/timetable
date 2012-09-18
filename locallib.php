<?php
global $CFG;
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/mod/timetable/config.php');
//require_once($CFG->dirroot.'/mod/timetable/3.php');


function timetable_insert_fields($timetable) {
    /**
     * Does the dirty work when adding new course sessions for the timetable.
     * @param timetable an object created by the module creation form
     * @return          nothing 
     */
    global $TIMETABLE_MODIFIER,$DB,$TIMETABLE_DAYS,$TIMETABLE_COLORS;
    // There's some weird behavior here. Moodle apparently doesn't set
    // the checkbox corresponding values if they aren't checked so
    // when we use them to enable the widget group (unchecked) they aren't
    // set. Because of this the condition is kinda far-fetched.
    
    if (!isset($timetable->sess_dis_0))
    { // is enabled
        $tt_data = new object();   // basic data
        $tt_data->day       = $timetable->sess_day_0;
        $tt_data->hour      = $timetable->sess_hour_0 + $TIMETABLE_MODIFIER;
        $tt_data->duration  = $timetable->sess_len_0;
        $tt_data->hour_end  = $tt_data->duration + $tt_data->hour;
        $tt_data->classroom = $timetable->classroom;
        $tt_data->timetable = $timetable->id;
        $tt_data->color     = $timetable->color;
		$tt_data->active   = $timetable->active;
		$DB->insert_record('timetable_base', $tt_data);
    }

    if (!isset($timetable->sess_dis_1)) 
    { // is enabled
        $tt_data = new object();   // basic data
        $tt_data->day       = $timetable->sess_day_1;
        $tt_data->hour      = $timetable->sess_hour_1 + $TIMETABLE_MODIFIER;
        $tt_data->duration  = $timetable->sess_len_1;
        $tt_data->hour_end  = $tt_data->duration + $tt_data->hour;
        $tt_data->classroom = $timetable->classroom;
        $tt_data->timetable = $timetable->id;
        $tt_data->color     = $timetable->color;
		$tt_data->active   = $timetable->active;
		$DB->insert_record('timetable_base', $tt_data);
    }

    if( check_valid($tt_data))
    {
    if (!isset($timetable->sess_dis_2)) { // is enabled
        $tt_data = new object();   // basic data
        $tt_data->day       = $timetable->sess_day_2;
        $tt_data->hour      = $timetable->sess_hour_2 + $TIMETABLE_MODIFIER;
        $tt_data->duration  = $timetable->sess_len_2;
        $tt_data->hour_end  = $tt_data->duration + $tt_data->hour;
        $tt_data->classroom = $timetable->classroom;
        $tt_data->timetable = $timetable->id;
        $tt_data->color     = $timetable->color;
		$tt_data->active   = $timetable->active;
<<<<<<< HEAD
        $DB->insert_record('timetable_base', $tt_data);
     }
=======
		$DB->insert_record('timetable_base', $tt_data);
>>>>>>> be8f679d73ded515f4281698864226cf57ebea5e
    }

}



function timetable_find($room, $hour, $day) {
	global $DB;
	
    /**
     * Finds if there is a course scheduled for a certain moment in time.
     * @param room  course classroom
     * @param hour  course hour of day
     * @param day   course day (as a number, where 0 is Monday)
     * @return      record object if it exists, false otherwise
     */
    if ($DB->record_exists('timetable_base', array('hour'=>$hour, 'classroom'=>$room,'day'=>$day))) {
        return $DB->get_record('timetable_base', array('hour'=>$hour, 'classroom'=>$room,'day'=>$day));
    } else {
        return false;
    }
}

function timetable_find_id($room, $hour, $day, $TIMETABLE_ID)
{
    global $DB;
    foreach ($TIMETABLE_ID as $key ) 
    {
    if  ($DB->record_exists('timetable_base', array('hour'=>$hour, 'classroom'=>$room,'day'=>$day, 'id'=>$key))) 
         
        return $DB->get_record('timetable_base', array('hour'=>$hour, 'classroom'=>$room,'day'=>$day, 'id'=>$key));
    }
    return false;

}

function timetable_display_passed_timetable( $TIMETABLE_ID ){

    global $CFG,$DB,$OUTPUT,$TIMETABLE_DAYS,$TIMETABLE_COLORS_PURE,$OUTPUT;
    $contents = "<link type='text/css' rel='stylesheet' href='$CFG->wwwroot/mod/timetable/timetable.css'/>";
    $title = $DB->get_record('resource',array('name'=>'Timetable'));
    
    $rooms = timetable_get_rooms();
    if ($rooms!=NULL){
        
        $roomscount = count($rooms);
        $css   = timetable_get_css();
        $format= timetable_get_format();

        
  //        $OUTPUT->heading($title->alltext);
        $contents .= "<table class='$css->timetable' cellspacing='$format->cellspacing'";
        $contents .= "<tr>";

        // top left dummy spaces
        $contents .= "<td class='$css->void $css->bottom'> </td>";
        $contents .= "<td class='$css->void $css->bottom $css->right'> </td>";

        for ($hour = $format->first_hour; $hour<=$format->last_hour; ++$hour){
            if ($hour == $format->last_hour) {
                $contents .= "<td class='$css->fixed $css->top $css->right $css->head'>$hour:00</td>";
            } else {
                $contents .= "<td class='$css->fixed $css->head $css->top'>$hour:00</td>";
            }
        }

        $contents .= '</tr>'; // header done

        $index = 0;
        //-------------------------------------------------------------------------------------------
        
        foreach ($TIMETABLE_DAYS as $k => $d) {
            if ($index % 2 == 0) {
                $rowstyle = $css->even;
            } else {
                $rowstyle = $css->odd;
            }

            $contents .= "<tr class='$rowstyle'>";
            if ($d == end($TIMETABLE_DAYS)) {
                $contents .= "<td rowspan='$roomscount' class='$css->left $css->bottom'> $d </td>";
            } else {
                $contents .= "<td rowspan='$roomscount' class='$css->left'>$d </td>";
            }

            foreach ($rooms as $r) {
                if (($r == end($rooms)) && ($d == end($TIMETABLE_DAYS))) {
                    $browstyle = $css->bottom;
                } else {
                    $browstyle = '';
                }
                $contents .= "<td class='$browstyle $css->right'>$r</td>";
                
                for ($hour = $format->first_hour; $hour <= $format->last_hour; ++$hour) {
                    if ($rec = timetable_find_id($r, $hour, $k, $TIMETABLE_ID)) {
                        $contents .= "<td colspan='$rec->duration' class='$css->box $css->session ".$TIMETABLE_COLORS_PURE[$rec->color]."'>";
                        $contents .= timetable_format_details($rec);
                        $contents .= '</td>';
                        $hour += $rec->duration - 1;        
                    } else {
                        if ($hour == $format->last_hour) {
                            $rborder = $css->right;
                        } else {
                            $rborder = '';
                        }
                        $contents .= "<td class='$rborder $browstyle'>&nbsp; </td>";
                    }
                }
                $contents .= "</tr>";
                if ($r != end($rooms)) {
                    $contents.= "<tr class='$rowstyle'>";
                }
            }
            ++$index;
        }
        $contents .= "</table>";
        return $contents;
    }else{
        $css   = timetable_get_css();
        $format= timetable_get_format();
        $contents .= "<table class='$css->timetable' cellspacing='$format->cellspacing'";
        $contents .= "<tr>";

        // top left dummy spaces
    //    $contents .= "<td class='$css->void $css->bottom'> </td>";
        $contents .= "<td class='$css->void $css->bottom $css->right'> </td>";

        for ($hour = $format->first_hour; $hour<=$format->last_hour; ++$hour){
            if ($hour == $format->last_hour) {
                $contents .= "<td class='$css->fixed $css->top $css->right $css->head'>$hour:00</td>";
            } else {
                $contents .= "<td class='$css->fixed $css->head $css->top'>$hour:00</td>";
            }
        }

        $contents .= '</tr>'; // header done

        $index = 0;
        foreach ($TIMETABLE_DAYS as $k => $d) {
        if ($index % 2 == 0) {
            $rowstyle = $css->even;
        } else {
            $rowstyle = $css->odd;
        }

        $contents .= "<tr class='$rowstyle'>";
            
        if ($d == end($TIMETABLE_DAYS)) {
                        $contents .= "<td  class='$css->left $css->right $css->bottom'> $d </td>";
                        $rstyle = "$css->bottom";
                    } else {
                        $contents .= "<td  class='$css->left $css->right'>$d </td>";
                        $rstyle = '';
                    }
                    
                    for ($hour = $format->first_hour; $hour <= $format->last_hour; ++$hour) {
                        if ($hour == $format->last_hour) {
                            $rborder = $css->right;
                        } else {
                            $rborder = '';
                        }
                        $contents .= "<td class='$rborder $rstyle'>&nbsp; </td>";
                    }
                    $contents .= "</tr>";
                    $index++;
        }
        
        $contents .= "</table>";
        return $contents;
    }   
} 


function check_valid($record){

    global $DB;
    $ok=1; 

    //valid

    $rec = $DB->get_records('timetable_base', array('day'=>$record->day));

        foreach ($rec as $r) 
        {    
            
            if( $r->classroom == $record->classroom )
            {
                
                if (  ( ($record->hour < $r->hour_end) && ( $record->hour > $r->hour)) ||

                    ( ($record->hour_end > $r->hour) && ( $record->hour_end < $r->hour_end) ) )

                    echo "BAD";
                    return false;
            }

        }
        return true;
    }

function timetable_get_details($rec) {
    global $CFG,$DB;
	
    // get the course number to extract relevant data
	
    $module_id = $rec->timetable;
	
    $record = $DB->get_record('timetable', array('id'=>$module_id));
	$course_id = $record->course;
    $course    = $DB->get_record('course', array('id'=>$course_id));
	
    if (isset($course->context)) {
        $context = $course->context;
    } else {
        $context = get_context_instance(CONTEXT_COURSE, $course_id);
    }

    $data = new object();


    // ------ Instructor name resolution --------------
    $data->teachers = array();
    $data->teacher_links = array();
	
    // temporary guardian
    
         
            
	$rusers = get_role_users(3, $context, true, '', 'r.sortorder ASC, u.lastname ASC', true);
	
	if (is_array($rusers) && count($rusers)) {
		// print the teachers

		foreach ($rusers as $teacher) {
			$fullname = fullname($teacher, true);
			$data->teachers[] = $fullname;
			$data->teacher_links[] = $CFG->wwwroot.'/user/view.php?id='.
			$teacher->id.'&amp;course='.SITEID;
		}
	}

    $data->id = $course_id;
    $data->shortname = $course->shortname;

    return $data;
}



function timetable_format_details($rec) {
    global $CFG;
    $data = timetable_get_details($rec);
    $output = "<b><a target=\"_top\" href='$CFG->wwwroot/course/view.php?id=$data->id' " .
               "class='course'>"."$data->shortname </a></b>";


    if (is_array($data->teachers) && count($data->teachers)) {
        $output .= '<br>';
        $index = 0;
        foreach ($data->teachers as $teacher) {
            $output .= '<a target="_top" href="'.$data->teacher_links[$index].'" class="name">';
            $output .= timetable_name_strip($teacher);
            $output .= '</a>';
            if ($teacher != end($data->teachers)) {
                $output .= ' | ';
            }
            ++$index;
        }
    }

    return $output;
}

function timetable_name_strip($fullname, $super = true) {
    $tokens = explode(' ', $fullname);

    $output = '';
    if ($super) {
        $firstname = explode('-', $tokens[0]);
        $output .= $firstname[0];
    } else {
        $output .= $tokens[0];
    }

    $output .= ' '.substr(end($tokens), 0, 1).'.';
    return $output;
}

function timetable_get_rooms() {
	global $DB;
    $timetable = $DB->get_records('timetable_base');
	$rooms = array();
    if ($timetable!=NULL){
        foreach ($timetable as $value) {
			$rooms[] = $value->classroom;          
        }
    }else{
        return NULL;
    } 
    return array_unique($rooms);
}


function timetable_get_css() {
    $css = new object();
    $css->timetable = 'time';
    $css->void      = 'void';
    $css->top       = 'top';
    $css->left      = 'left';
    $css->right     = 'right';
    $css->bottom    = 'bottom';
    $css->head      = 'head';
    $css->even      = 'even';
    $css->odd       = 'odd';
    $css->session   = 'session';
    $css->fixed     = 'fixed';
    $css->box       = "$css->top $css->left $css->right $css->bottom";
    return $css;
}


function timetable_get_format() {
    $format = new object();
    $format->cellspacing = 0;
    $format->first_hour  = 8;
    $format->last_hour   = 22;

    return $format;
}

function timetable_display() {

    global $CFG,$DB,$OUTPUT,$TIMETABLE_DAYS,$TIMETABLE_COLORS_PURE,$OUTPUT;
    $contents = "<link type='text/css' rel='stylesheet' href='$CFG->wwwroot/mod/timetable/timetable.css'/>";
    $title = $DB->get_record('resource',array('name'=>'Timetable'));
    
    $rooms = timetable_get_rooms();
    if ($rooms!=NULL){
		
        $roomscount = count($rooms);
        $css   = timetable_get_css();
        $format= timetable_get_format();

		
//        $OUTPUT->heading($title->alltext);
        $contents .= "<table class='$css->timetable' cellspacing='$format->cellspacing'";
        $contents .= "<tr>";

        // top left dummy spaces
        $contents .= "<td class='$css->void $css->bottom'> </td>";
        $contents .= "<td class='$css->void $css->bottom $css->right'> </td>";

        for ($hour = $format->first_hour; $hour<=$format->last_hour; ++$hour){
            if ($hour == $format->last_hour) {
                $contents .= "<td class='$css->fixed $css->top $css->right $css->head'>$hour:00</td>";
            } else {
                $contents .= "<td class='$css->fixed $css->head $css->top'>$hour:00</td>";
            }
        }

        $contents .= '</tr>'; // header done

        $index = 0;
		//-------------------------------------------------------------------------------------------
		
        foreach ($TIMETABLE_DAYS as $k => $d) {
            if ($index % 2 == 0) {
                $rowstyle = $css->even;
            } else {
                $rowstyle = $css->odd;
            }

            $contents .= "<tr class='$rowstyle'>";
            if ($d == end($TIMETABLE_DAYS)) {
                $contents .= "<td rowspan='$roomscount' class='$css->left $css->bottom'> $d </td>";
            } else {
                $contents .= "<td rowspan='$roomscount' class='$css->left'>$d </td>";
            }

            foreach ($rooms as $r) {
                if (($r == end($rooms)) && ($d == end($TIMETABLE_DAYS))) {
                    $browstyle = $css->bottom;
                } else {
                    $browstyle = '';
                }
                $contents .= "<td class='$browstyle $css->right'>$r</td>";
				
                for ($hour = $format->first_hour; $hour <= $format->last_hour; ++$hour) {
                    if ($rec = timetable_find($r, $hour, $k)) {
						if(!$rec->active){
							$contents .= "<td colspan='$rec->duration' class='$css->box $css->session ".$TIMETABLE_COLORS_PURE[$rec->color]."'>";
							$contents .= timetable_format_details($rec);
							$contents .= '</td>';
							$hour += $rec->duration - 1;
						}else {
							if ($hour == $format->last_hour) {
								$rborder = $css->right;
							} else {
								$rborder = '';
							}
                        $contents .= "<td class='$rborder $browstyle'>&nbsp; </td>";
						}         
                    } else {
                        if ($hour == $format->last_hour) {
                            $rborder = $css->right;
                        } else {
                            $rborder = '';
                        }
                        $contents .= "<td class='$rborder $browstyle'>&nbsp; </td>";
                    }
                }
                $contents .= "</tr>";
                if ($r != end($rooms)) {
                    $contents.= "<tr class='$rowstyle'>";
                }
            }
            ++$index;
        }
        $contents .= "</table>";
        return $contents;
    }else{
		$css   = timetable_get_css();
        $format= timetable_get_format();
		$contents .= "<table class='$css->timetable' cellspacing='$format->cellspacing'";
        $contents .= "<tr>";

        // top left dummy spaces
    //    $contents .= "<td class='$css->void $css->bottom'> </td>";
        $contents .= "<td class='$css->void $css->bottom $css->right'> </td>";

        for ($hour = $format->first_hour; $hour<=$format->last_hour; ++$hour){
            if ($hour == $format->last_hour) {
                $contents .= "<td class='$css->fixed $css->top $css->right $css->head'>$hour:00</td>";
            } else {
                $contents .= "<td class='$css->fixed $css->head $css->top'>$hour:00</td>";
            }
        }

        $contents .= '</tr>'; // header done

        $index = 0;
		foreach ($TIMETABLE_DAYS as $k => $d) {
		if ($index % 2 == 0) {
			$rowstyle = $css->even;
		} else {
			$rowstyle = $css->odd;
		}

		$contents .= "<tr class='$rowstyle'>";
			
		if ($d == end($TIMETABLE_DAYS)) {
						$contents .= "<td  class='$css->left $css->right $css->bottom'> $d </td>";
						$rstyle = "$css->bottom";
					} else {
						$contents .= "<td  class='$css->left $css->right'>$d </td>";
						$rstyle = '';
					}
					
					for ($hour = $format->first_hour; $hour <= $format->last_hour; ++$hour) {
						if ($hour == $format->last_hour) {
                            $rborder = $css->right;
                        } else {
                            $rborder = '';
                        }
                        $contents .= "<td class='$rborder $rstyle'>&nbsp; </td>";
					}
					$contents .= "</tr>";
					$index++;
		}
		
		$contents .= "</table>";
        return $contents;
    }	
}


function timetable_print() {
    /**
     * Retrieves the contents of the cached html timetable from moodle/mod/timetable/var/timetable.html
     * @param  none
     * @return true if the timetable was found, false otherwise
     */
    global $TIMETABLE_HTML_FILE_WWW, $TIMETABLE_HTML_FILE;
    if (!file_exists($TIMETABLE_HTML_FILE)) {
        echo get_string('notimetable', 'timetable');
        return false;
    } else {
        $contents = file_get_contents($TIMETABLE_HTML_FILE_WWW);
        echo $contents;
        return true;
    }
	
}