<?

// ENSURE THIS IS BEING INCLUDED IN AN SE SCRIPT
if(!defined('SE_PAGE')) { exit(); }

// INCLUDE beerS CLASS FILE
include "../include/class_beers.php";

// INCLUDE beerS FUNCTION FILE
include "../include/functions_beers.php";

// SET USER DELETION HOOK
SE_Hook::register("se_user_delete", "deleteuser_beers");

?>