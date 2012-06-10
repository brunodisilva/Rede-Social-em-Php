<?php

/* $Id: admin_header_job.php 7 2009-01-11 06:01:49Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

// INCLUDE jobS CLASS FILE
include "../include/class_job.php";

// INCLUDE jobS FUNCTION FILE
include "../include/functions_job.php";


// SET HOOKS
SE_Hook::register("se_user_delete", 'deleteuser_job');

SE_Hook::register("se_site_statistics", 'site_statistics_job');

?>