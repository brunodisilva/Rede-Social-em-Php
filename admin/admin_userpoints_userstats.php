<?
$page = "admin_userpoints_userstats";
include "admin_header.php";

$task = semods::getpost('task', 'main');
$graph = semods::getpost('graph', 'earned_vs_spent');
$period = semods::getpost('period', 'week');
$start = semods::getpost('start', 1);
$user_id = semods::getpost('user_id', 0);

// VALIDATE USER ID OR RETURN TO VIEW USERS
$user = new se_user(array($user_id));
if($user->user_exists == 0) {
  semods::redirect("admin_userpoints_viewusers.php?s=$s&p=$p&f_user=$f_user&f_email=$f_email&f_level=$f_level&f_enabled=$f_enabled");
}

// CLEAR USER STATS IF REQUESTED
if($task == "clearuserstats") {
  $database->database_query("TRUNCATE se_semods_userpointstats");
  header("Location: admin_userpoints_userstats.php?graph=$graph");
  exit;
}


// GENERATE CHART DATA
if($task == "getdata") {

  // INCLUDE FLASH CHART FUNCTIONS
  include_once "../include/charts/charts.php";

  // SET CHART TYPE
  $chart['chart_type'] = "bars";

  // SET STYLES
  $chart['chart_border'] = array('top_thickness' => 1, 
                                 'bottom_thickness' => 1, 
                                 'left_thickness' => 1, 
                                 'right_thickness' => 1,
				 'color' => "666666");
  $chart['axis_category'] = array ('size' => 10, 
                                   'color' => "333333"); 
  $chart['axis_value'] = array('size' => 10, 
                               'color' =>  "333333"); 
  $chart['legend_label'] = array('size' => 12, 
                                 'color' => "000000"); 
  $chart['chart_pref'] = array('line_thickness' => 2, 
			       'point_shape' => "none",
			       'fill_shape' => true);
  $chart['chart_value'] = array('prefix' => "", 
				'suffix' => "", 
				'decimals' => 0, 
				'separator' => "", 
				'position' => "cursor", 
				'hide_zero' => true, 
				'as_percentage' => false, 
				'font' => "arial", 
				'bold' => true, 
				'size' => 12, 
				'color' => "000000", 
				'alpha' => 75 );
  $chart['chart_grid_h'] = array('alpha' => 5,
				 'color' => "000000", 
				 'thickness' => 1, 
				 'type' => "solid");
  $chart['chart_grid_v'] = array('alpha' => 5,
				 'color' => "000000",
				 'thickness' => 1,
				 'type' => "solid");


  // SET LEGEND LABEL AND QUERY VARIABLE
  $chart['chart_data'][0][0] = "";
  switch($graph) {
    case "earned_vs_spent":
      $var1 = "userpointstat_earn";
      $var2 = "userpointstat_spend";
      $chart['chart_data'][1][0] = semods::get_language_text( 100016079 );
      $chart['chart_data'][2][0] = semods::get_language_text( 100016080 );
      break;
    }

  // SET PERIOD
  switch($period) {
    case "week":
      $interval = "86400";
      $stat_date_format = "D";
      $date_compare = "j";
      $num_points = 8;
      if(date('w', time()) == 0) { $day_num = 7; } else { $day_num = date('w', time()); }
      $old_stat_date = mktime(0, 0, 0, date('n', time()), date('j', time())-$day_num+1-7*($start-1), date('Y', time()));
      $last_stat_date = mktime(0, 0, 0, date('n', time()), date('j', time())-$day_num+1-7*($start-1)+7, date('Y', time()));
      $chart['chart_data'][1][0] .= semods::get_language_text( 100016070 ).semods::get_language_text( 100016069 )." ".$datetime->cdate("M jS", $old_stat_date).semods::get_language_text( 100016071 );
      break;
    case "month":
      $interval = "86400";
      $stat_date_format = "j";
      $date_compare = "j";
      $num_points = date("t", time())+1;
      $old_stat_date = mktime(0, 0, 0, date('n', time())-($start-1), 1, date('Y', time()));
      $last_stat_date = mktime(0, 0, 0, date('n', time())-($start-1)+1, 1, date('Y', time()));
      $chart['chart_data'][1][0] .= semods::get_language_text( 100016070 ).$datetime->cdate("F", $old_stat_date).semods::get_language_text( 100016071 );
      break;
    case "year":
      $interval = "2678400";
      $stat_date_format = "M.";
      $date_compare = "n";
      $num_points = 13;
      $old_stat_date = mktime(0, 0, 0, 1, 1, date('Y', time())-($start-1));
      $last_stat_date = mktime(0, 0, 0, 1, 1, date('Y', time())-($start-1)+1);
      $chart['chart_data'][1][0] .= semods::get_language_text( 100016070 ).$datetime->cdate("Y", $old_stat_date).semods::get_language_text( 100016071 );
      break;
  }

  // RUN QUERY
  $stats = $database->database_query("SELECT userpointstat_date, $var1 AS stat_var, $var2 AS stat_var2  FROM se_semods_userpointstats WHERE userpointstat_user_id = $user_id AND userpointstat_date<=$last_stat_date AND userpointstat_date>=$old_stat_date ORDER BY userpointstat_date ASC");

  // SET VARS
  $count = 0;
  $old_stat_date = $old_stat_date-$interval;

  // PUT STATS INTO ARRAY FOR GRAPH
  while($stat = $database->database_fetch_assoc($stats)) {
    while($stat['userpointstat_date']-$old_stat_date>$interval) {
      $new_stat_date = $old_stat_date + $interval;
      $count++;
      $chart['chart_data'][0][$count] = date($stat_date_format, $new_stat_date);
      $chart['chart_data'][1][$count] = 0;
      $chart['chart_data'][2][$count] = 0;
      $old_stat_date = $new_stat_date;
    }
    if(date($date_compare, $old_stat_date) == date($date_compare, $stat['userpointstat_date'])) {
      $chart['chart_data'][1][$count] += $stat['stat_var'];
      $chart['chart_data'][2][$count] += $stat['stat_var2'];
    } else {
      $count++;
      $chart['chart_data'][0][$count] = date($stat_date_format, $stat['userpointstat_date']);
      $chart['chart_data'][1][$count] = $stat['stat_var'];
      $chart['chart_data'][2][$count] = $stat['stat_var2'];
      $old_stat_date = $stat['userpointstat_date'];
    }
  }

  while(count($chart['chart_data'][0])<$num_points) {
      $new_stat_date = $old_stat_date + $interval;
      $count++;
      $chart['chart_data'][0][$count] = date($stat_date_format, $new_stat_date);
      $chart['chart_data'][1][$count] = 0;
      $chart['chart_data'][2][$count] = 0;
      $old_stat_date = $new_stat_date;
  }


  // OUTPUT CHART
  SendChartData($chart);
  exit();
}








// INCLUDE FLASH CHART FUNCTIONS FOR GRAPHS
include_once "../include/charts/charts.php";
$chart = InsertChart("../include/charts/charts.swf", "../include/charts/charts_library", "admin_userpoints_userstats.php?task=getdata&user_id=$user_id&graph=$graph&period=$period&start=$start&uniqueID=".uniqid(rand(),true), 550, 400, "FFFFFF");








// ASSIGN VARIABLES AND SHOW STATS PAGE
$smarty->assign('user_id', $user_id);
$smarty->assign('user_username', $setting['setting_username'] ? $user->user_info['user_username'] : $user->user_displayname );
$smarty->assign('chart', $chart);
$smarty->assign('graph', $graph);
$smarty->assign('period', $period);
$smarty->assign('start', $start);
include "admin_footer.php";
?>