<?php
global $TIMETABLE_TABLE_HEADER;
global $TIMETABLE_TABLE_FOOTER;
global $MODIFIER,$DB,$OUTPUT;

  require_once('../../config.php');
  require_once('lib.php');
  $rec = $DB->get_records('timetable');
  foreach($rec as $r) 
  {
	$aux = $r->timecreated + 360*24*250;
  }
  echo timetable_display();
  
