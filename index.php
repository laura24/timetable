<?php
global $TIMETABLE_TABLE_HEADER;
global $TIMETABLE_TABLE_FOOTER;
global $MODIFIER,$DB,$OUTPUT;

  require_once('../../config.php');
  require_once('lib.php');
  
	$current_year = date('Y', time()+3600*356*24 ); 
	echo $current_year;
	define("TIME_TO_DELETE", $current_year."-02-15");
	$aux = strtotime(TIME_TO_DELETE );
	
	if( time()> $aux ) 
	{
		$rec = $DB->get_records('timetable');
		foreach ($rec as $r) 
		{
			timetable_delete_instance($r->id);
		}
		
	}
	 echo timetable_display();
  
