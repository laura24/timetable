<?php

require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/timetable/config.php');

class mod_timetable_mod_form extends moodleform_mod {
    function definition() {
        global $CFG, $TIMETABLE_DAYS, $COURSE ,$TIMETABLE_COLORS;


        $mform =& $this->_form;
        $mform->addElement('header', 'timetabledata', get_string('timetablehdr','timetable'));

        $mform->addElement('text', 'classroom', get_string('classroom','timetable'),'maxlength="16" size="16"');
        $mform->addRule('classroom', get_string('missingclassroom','timetable'),'required', null, 'client');
        $mform->setType('classroom', PARAM_MULTILANG);
		
        $mform->addElement('select', 'color', get_string('color','timetable'), $TIMETABLE_COLORS);
		
        $lengths = array(1=>'1', 2=>'2', 3=>'3', 4=>'4', 5=>'5', 6=>'6', 7=>'7', 8=>'8');
        $sessions = array(array(), array(), array());
        $hours = array("8:00", "9:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00",
                       "18:00", "19:00", "20:00", "21:00");  
        for ($x = 0; $x < 3; ++$x) {
            $sessions[$x][] = & $mform->createElement('select',"sess_day_$x", '', $TIMETABLE_DAYS);
            $sessions[$x][] = & $mform->createElement('select',"sess_hour_$x", '', $hours);
            $sessions[$x][] = & $mform->createElement('select',"sess_len_$x", '', $lengths);
            
		
            $sessions[$x][] = & $mform->createElement('checkbox', "sess_dis_$x", null, get_string('disable','timetable'));
            $mform->addGroup($sessions[$x], "sess_$x", get_string('session','timetable'), ' ', false);
            $mform->disabledIf("sess_$x", "sess_dis_$x", 'checked');
        }
        //---------------------------
        $features = new stdClass;
        $features->groups = false;
        $features->groupings = false;
        $features->groupmembersonly = false;
		
        $this->standard_coursemodule_elements($features);
		$this->add_intro_editor();
        $this->add_action_buttons(); 
    }

    function definition_after_data() {
        parent::definition_after_data();
    }

    function data_preprocessing(&$default_values) {
        /* If the timetable for this course has already been set,
         * load the entries into the form */
        global $COURSE, $TIMETABLE_MODIFIER,$DB;
        // set the standard defaults here, before first attempting to
        // change them

        $returnurl = timetable_build_course_url($COURSE);
        
        for ($x = 0; $x < 3; ++$x) {
            $default_values["sess_len_$x"]   =  4;
           $default_values["sess_hour_$x"] =  0;
            $default_values["sess_dis_$x"]   =  1;
            $default_values["sess_col_$x"]   =  0;
        }

        if (!empty($this->_instance)) {
            $module_id = $DB->get_record('timetable', array( 'course'=>$COURSE->id))->id;
            $data = $DB->get_records('timetable_base', array( 'timetable'=>$module_id));
            if (is_array($data) && count($data)) {
                $x = 0;
                foreach ($data as $entry) {
                    $default_values["classroom"]     = $entry->classroom;
                    $default_values["sess_len_$x"]   = $entry->duration;
                    $default_values["sess_hour_$x"] = $entry->hour - $TIMETABLE_MODIFIER;
                    $default_values["color"]   = $entry->color;
                    $default_values["sess_day_$x"]   = $entry->day;
                    $default_values["sess_dis_$x"]   = 0;
                    ++$x;
                }
            }
        } else {
            /* This instance has not been created yet. We also want to have
             * a maximum of one module instance per course, so we first 
             * check the number of instances in this course and only allow
             * the editor to proceed if there aren't any.
             */
            if (($DB->count_records('timetable',array('course'=>$COURSE->id)))== 0) {
                // ok, do nothing
            } else {
                // attempting to create multiple instances, block the user
                print_error('instanceerror', 'timetable', $returnurl);
            }
        }

    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // at least one disable must be unchecked
        $count = 0;
        for ($i = 0; $i < 3; ++$i) {
            if (isset($data["sess_dis_$i"])) {
                ++$count;
            }
        }
        if ($count == 3) {
            for ($i = 0; $i < 3; ++$i) {
                $errors["sess_$i"] = get_string('noentriesactive', 'timetable');
            }
        }
        
        return $errors;
    }
}
?>
