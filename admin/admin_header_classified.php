<?php

/* $Id: admin_header_classified.php 7 2009-01-11 06:01:49Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

// INCLUDE classifiedS CLASS FILE
include "../include/class_classified.php";

// INCLUDE classifiedS FUNCTION FILE
include "../include/functions_classified.php";


// SET HOOKS
SE_Hook::register("se_user_delete", 'deleteuser_classified');

SE_Hook::register("se_site_statistics", 'site_statistics_classified');

?>