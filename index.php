<?php
global $TIMETABLE_TABLE_HEADER;
global $TIMETABLE_TABLE_FOOTER;
global $MODIFIER,$DB,$OUTPUT;

  require_once('../../config.php');
  require_once('lib.php');
  
  $ok = 1;
  if($ok) 
  {
	$rec = $DB->get_records('timetable');
	foreach ($rec as $r) 
	{
		timetable_delete_instance($r->id);
	}
  }
  echo timetable_display();
  
