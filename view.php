<?php

global $TIMETABLE_TABLE_HEADER;
global $TIMETABLE_TABLE_FOOTER;
global $MODIFIER,$DB,$OUTPUT;

require_once("../../config.php");
require_once("lib.php");

$id = required_param('id', PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('timetable', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $annotation  = $DB->get_record('timetable', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_course_login($course, false, $cm);

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

$PAGE->set_url('/mod/timetable/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string("Timetable"));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();


// Print info about the saved sessions
$module_id      = $DB->get_record('timetable', array('course'=>$cm->course))->id;
$timetable_data = $DB->get_records('timetable_base', array('timetable'=>$module_id));

if ($timetable_data==NULL) {
    error("No table data to display");
}

$table = new html_table();

$table->head=array('Nr. crt','Module ID','Classroom','Days','Start hour','End hour','Duration','Color');
$table->attributes["style"] = 'margin:auto;';

$i = 0;
foreach ($timetable_data as $value)  {
    ++$i;
	$data=array($i,$value->timetable,$value->classroom,$TIMETABLE_DAYS[$value->day],$value->hour,$value->hour_end,$value->duration,$TIMETABLE_COLORS[$value->color]);
 //  print '<tr>';
 //   print "<td> $i </td>";
 //   print '<td>'.$value->timetable.'</td>';
 //   print '<td>'.$value->classroom.'</td>';
//   print '<td>'.$value->hour.'</td>';
 //   print '<td>'.$value->hour_end.'</td>';
 //   print '<td>'.$value->duration.'</td>';
 //   print '<td>'/*$TIMETABLE_COLORS[$value->color]*/.'</td>';
//   print '</tr>'; 
	$table->data[]=$data;
}

echo html_writer::table($table);

echo $OUTPUT->footer($course);
?>
