    ====================================
      Timetable Module - Version 0.1.1
    ====================================
    
  
 Author        : Sergiu Costea
 Moodle version: 1.9.9
    

 Table of contents
 =================
    1. Overview
    2. Features
    3. Installation
    4. Usage instructions
    5. Bug reporting & contact
    6. Changelog
	7. File list
    
    
    
 1. Overview
 ===========
 
 The Timetable module is a simple Moodle plugin that displays a simple
 colored timetable on the front page. 
 
 THIS MODULE IS PROVIDED AS-IS WITHOUT ANY WARRANTY.
 
 
 2. Features
 ===========
 
 What it is:
    - timetable data is dynamically constructed by adding Timetable modules 
    to courses
    - the Timetable displays the courses' shortname, classroom and teachers 
    together with links to the respective pages
    - course entries in the timetable can be color coded
    - courses can have multiple entries in the timetable 
    - the timetable page is cached
    
 And what it will be:
    - settings page to change the appearance or contents
    - display filters
    - more colors
    - unlimited entries per course
    - multiple timetable instances
    
 Alas, what it's not:
    - fortnightly courses not supported
    - no checking of overlapping courses
    - buggy :D
    
    
 3. Installation
 ===============
 
 a. Extract the archive in the moodle/mod/ folder
 b. Click on the "Notifications" item in site administration.
 c. Copy the following lines of code in moodle/index.php at about line 250:
 
 case FRONTPAGETIMETABLE:               
 require_once($CFG->dirroot.'/mod/timetable/lib.php');
 print_heading_block(get_string('timetable', 'timetable'));
 print_box_start('generalbox');
 timetable_print();
 print_box_end();
 break;
                
 d. Copy the following line of code in moodle/course/lib.php at about line 18:
 define('FRONTPAGETIMETABLE', '5');http://start.ubuntu.com/9.10/
 
 e. Copy the following line of code in moodle/lib/adminlib.php at about line
2729 (the $this->choices array) after FRONTPAGECATEGORYCOMBO:
 FRONTPAGETIMETABLE => 'Global timetable';
 
 4. Usage
 ========
 
 1) Enable display in the front page
 You can add the timetable from the Front Page settings in site administration.
 
 2) Add a course to the timetable
 Just add a Timetable module to the course, specifying the classroom and time.
 
 3) Removing a course from the timetable
 Delete the timetable module.
 
 4) Editing a course that is already in the timetable
 Edit the timetable module contained in the course.
 
 
 5. Bug reporting & contact
 ==========================
 
 Please send any bugs, suggestions, comments to sergiu.costea@gmail.com .
 
 
 6. Changelog
 ============
 
 nothing here yet :D

 7. File List
 ============
 /mod/project/icon.gif
 /mod/project/index.php
 /mod/project/lib.php
 /mod/project/mod_form.php
 /mod/project/project.css
 /mod/project/README.txt
 /mod/project/scripts.js
 /mod/project/version.php
 /mod/project/view.php
 /mod/project/lang/en_utf8/project.php
 /mod/project/img/addfeed.gif
 /mod/project/img/delete.gif
 /mod/project/img/edit.gif
 /mod/project/img/tick_green_big.gif
 /mod/project/db/upgrade.php
 /mod/project/db/install.xml
 /project/index.php
 /project/project.css
 /project/view.php