<?php  //$Id: version.php,v 1.114.2.6 2009/01/28 23:44:23 stronk7 Exp $

/// This file defines the current version of the
/// backup/restore code that is being used.  This can be
/// compared against the values stored in the 
/// database (backup_version) to determine whether upgrades should
/// be performed (see db/backup_*.php)

$module->version    = 2009091702;   // The current version is a date (YYYYMMDDXX)
$module->requires   = 2010112400;    
$module->cron       = 0;     

?>
