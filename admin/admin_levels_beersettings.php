<?
$page = "admin_levels_beersettings";
include "admin_header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } else { $task = "main"; }
if(isset($_POST['level_id'])) { $level_id = $_POST['level_id']; } elseif(isset($_GET['level_id'])) { $level_id = $_GET['level_id']; } else { $level_id = 0; }

// VALIDATE LEVEL ID
$level = $database->database_query("SELECT * FROM se_levels WHERE level_id='$level_id'");
if($database->database_num_rows($level) != 1) { 
  header("Location: admin_levels.php");
  exit();
}
$level_info = $database->database_fetch_assoc($level);


// SET RESULT AND ERROR VARS
$result = 0;
$is_error = 0;



// SAVE CHANGES
if($task == "dosave") {
  $level_beers_allow = $_POST['level_beers_allow'];

  // GET PRIVACY SETTING
  $beers_privacy_0 = $_POST['beers_privacy_0'];
  $beers_privacy_1 = $_POST['beers_privacy_1'];
  $beers_privacy_2 = $_POST['beers_privacy_2'];
  $level_beers_privacy = $beers_privacy_0.$beers_privacy_1.$beers_privacy_2;

  if($is_error == 0) {

    // SAVE SETTINGS
    $database->database_query("UPDATE se_levels SET 
			level_beers_privacy='$level_beers_privacy',
			level_beers_allow='$level_beers_allow'
			WHERE level_id='$level_id'");

    $level_info = $database->database_fetch_assoc($database->database_query("SELECT * FROM se_levels WHERE level_id='$level_id'"));
    $result = 1;
  }

}


// GET PREVIOUS PRIVACY SETTINGS
$count = 0;
while($count < 6) {
  if(beers_privacy_levels($count) != "") {
    if(strpos($level_info[level_beers_privacy], "$count") !== FALSE) { $privacy_selected = 1; } else { $privacy_selected = 0; }
    $privacy_options[$count] = Array('privacy_name' => "beers_privacy_".$count,
				'privacy_value' => $count,
				'privacy_option' => beers_privacy_levels($count),
				'privacy_selected' => $privacy_selected);
  SE_Language::_preload(beers_privacy_levels($count));
  }
  $count++;
}



// ASSIGN VARIABLES AND SHOW classified SETTINGS PAGE
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('error_message', $error_message);
$smarty->assign('level_id', $level_info[level_id]);
$smarty->assign('beers_allow', $level_info[level_beers_allow]);
$smarty->assign('level_beers_privacy', unserialize($level_info[level_beers_privacy]));
$smarty->assign('beers_privacy', $privacy_options);
$smarty->assign_by_ref('level_info', $level_info);
$smarty->assign('level_name', $level_info[level_name]);
include "admin_footer.php";
?>