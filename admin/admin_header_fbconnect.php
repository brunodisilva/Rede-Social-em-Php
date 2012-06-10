<?
/* $Id: admin_header_fbconnect.php 1 2009-07-04 09:36:11Z SocialEngineAddOns $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }


// INCLUDE FBCONNET FUNCTION FILE
include "../include/functions_fbconnect.php";


// SET HOOKS
SE_Hook::register("se_user_delete", 'deleteuser_fbconnect');
SE_Hook::register("se_site_statistics", 'site_statistics_fbconnect');
?>