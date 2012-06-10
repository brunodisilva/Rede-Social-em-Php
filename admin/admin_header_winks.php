<?

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

// INCLUDE WINKS CLASS FILE
include "../include/class_winks.php";

// INCLUDE WINKS FUNCTION FILE
include "../include/functions_winks.php";

// SET USER DELETION HOOK
SE_Hook::register("se_user_delete", "deleteuser_winks");

?>