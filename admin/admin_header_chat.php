<?php

/* $Id: admin_header_chat.php 6 2009-01-11 06:01:29Z john $ */

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
defined('SE_PAGE') or exit();

include "../include/functions_chat.php";

SE_Hook::register("se_site_statistics", 'site_statistics_chat');

?>