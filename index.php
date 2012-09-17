<?php
global $TIMETABLE_TABLE_HEADER;
global $TIMETABLE_TABLE_FOOTER;
global $MODIFIER,$DB,$OUTPUT;

  require_once('../../config.php');
  require_once('lib.php');
<<<<<<< HEAD
  echo timetable_display();
=======

>>>>>>> ac5ddc08c773548049eebde2293f0a419a21d86b
  
$current_year = date('Y', time()); 
    echo $current_year;
   
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

            print "we are here";
            $rec = $DB->get_records('timetable_base');
            foreach ($rec as $r) 
            {
            timetable_update_active($r);
            }


    }
    


  echo timetable_display();

