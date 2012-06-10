<?

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

// INCLUDE kiss CLASS FILE
include "../include/class_kiss.php";

// INCLUDE kiss FUNCTION FILE
include "../include/functions_kiss.php";

// SET USER DELETION HOOK
SE_Hook::register("se_user_delete", "deleteuser_kiss");

?>