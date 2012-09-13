<?php
    // do nothing, just a placeholder

$capabilities = array(
    'mod/timetable:view' => array(
        
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
		'legacy' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),
    
    'mod/timetable:edit' => array(
    
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'legacy' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),
	
	'mod/timetable:addinstance' =>array(
	
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
		'legacy' => array(
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    )
);

?>
