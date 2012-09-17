<?php
$TIMETABLE_HTML_FILE_WWW = $CFG->wwwroot.'/mod/timetable/var/timetable.html';
$TIMETABLE_HTML_FILE = $CFG->dirroot.'/mod/timetable/var/timetable.html';
global $TIMETABLE_MODIFIER;
global $TIMETABLE_COLORS_PURE;
global $TIMETABLE_STATUS;
$TIMETABLE_MODIFIER = 8;
global $TIMETABLE_DAYS,$TIMETABLE_COLORS;
$TIMETABLE_DAYS = array(
get_string('monday', 'timetable'),
get_string('tuesday', 'timetable'),
get_string('wednesday', 'timetable'),
get_string('thursday', 'timetable'),
get_string('friday', 'timetable'),
get_string('saturday', 'timetable'),
get_string('sunday', 'timetable'));  

$TIMETABLE_COLORS = array(
get_string('green','timetable'),
get_string('yellow', 'timetable'),
get_string('orange','timetable'),
get_string('red','timetable'),
get_string('purple','timetable'),
get_string('gray','timetable'));


$TIMETABLE_COLORS_PURE = array(
    'green',
    'yellow',
    'orange',
    'red',
    'purple',
    'gray');
	
$TIMETABLE_STATUS = array(
	'In progress',
	'Finished'
	);
$TIMETABLE_HTML_FILE = $CFG->dirroot.'/mod/timetable/var/timetable.html';