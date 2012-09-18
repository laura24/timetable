<?php

defined('MOODLE_INTERNAL') || die;

//if ($ADMIN->fulltree) {
	$category = $ADMIN->locate('timetable_history',true);
	
	if (empty($category)){
		$ADMIN->add('root', new admin_category('timetable_history', "Timetable History"));
	}
	
	$ADMIN->add('timetable_history', new admin_externalpage('timetable_history', get_string('timetable_history', 'timetable'),
			$CFG->wwwroot."/mod/timetable/timetable_form.php", 'moodle/site:config'));
//}
?>