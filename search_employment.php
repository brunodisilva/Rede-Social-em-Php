<?
$page = "search_employment";
include "header.php";

// DISPLAY ERROR PAGE IF USER IS NOT LOGGED IN AND ADMIN SETTING REQUIRES REGISTRATION
if($user->user_exists == 0 & $setting[setting_permission_employment] == 0) {
  $page = "error";
  $smarty->assign('error_header', 11050627);
  $smarty->assign('error_message', 11050626);
  $smarty->assign('error_submit', 11050628);
  include "footer.php";
}



$task = rc_toolkit::get_request('task','main');
$p = rc_toolkit::get_request('p',1);

$result = "";
$rc_validator = new rc_validator();
$rc_employment = new rc_employment();

//if($user->level_info[level_employment_allow] == 0) { header("Location: user_home.php"); exit(); }

$employments_per_page = 20;

$searched_fields = rc_toolkit::get_request('search',array());

if ($task == 'search' || $task == 'browse') {

  $searchable_fields = array(
    'employment_employer',
    'employment_position',
    'employment_description',
    'employment_location',
    'employment_is_current',
    'employment_from_month',
    'employment_from_year',
    'employment_to_month',
    'employment_to_year'
  );
  
  $operation = strtolower(rc_toolkit::get_request('operation','and'));
  if (!in_array($operation, array('and','or'))) $operation = 'and';

  foreach ($searched_fields as $field => $value) {
    // security filter !!
    if (in_array($field, $searchable_fields) && strlen($value)) {
      $value = mysql_real_escape_string($value);
      $search_data[$field] = " $field LIKE '%$value%' ";
      $search_query  .= "search[$field]=".urlencode($value).'&';
    }
  }
  
  $criteria = " JOIN se_users ON se_users.user_id = se_employments.employment_user_id";
  if (count($search_data)) {
    $criteria .= " WHERE " . join(" $operation ", $search_data);
  }
  $criteria .= " ORDER BY user_username ASC";
  $all_employments = $rc_employment->get_records($criteria, true);
  
  $page_vars = make_page(count($all_employments), $employments_per_page, $p);

  $employments = array_slice($all_employments, $page_vars[0], $employments_per_page);
  
  $employments = $rc_employment->build_searchable_fields($employments);
  foreach ($employments as $k=>$employment) {
    $u = new se_user();
    $u->user_info[user_id] = $employment[user_id];
    $u->user_info[user_username] = $employment[user_username];
    $u->user_info[user_photo] = $employment[user_photo];
    $employments[$k]['user'] = $u;

    $time_period = array();
    if ($employment['employment_from_month'] > 0) {
      $time_period[] = $employment['search_employment_from_month'];
    }
    if ($employment['employment_from_year'] > 0) {
      $time_period[] = $employment['search_employment_from_year'];
    }
   
    if ($employment['employment_is_current'] || $employment['employment_to_month'] > 0 || $employment['employment_to_year'] > 0) {
      $time_period[] = SE_Language::_get(11050110);
      
      if ($employment['employment_is_current']) {
        $time_period[] = $employment['search_employment_is_current'];
      }
      else {
        if ($employment['employment_to_month'] > 0) {
          $time_period[] = $employment['search_employment_to_month'];
        }
        if ($employment['employment_to_year'] > 0) {
          $time_period[] = $employment['search_employment_to_year'];
        }
      }
    }
    
    $employments[$k]['time_period'] = trim(join(' ',$time_period));
  }
  
}

$yearoptions = array();
foreach (range(date('Y'), date('Y') - 100) as $number) {
  $yearoptions[$number] = $number;
}

$monthoptions = array();
$i=0;
foreach (explode(',',SE_Language::_get(11050103)) as $letter) {
  $monthoptions[++$i] = $letter;
}

//Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec

$smarty->assign('yearoptions',$yearoptions);
$smarty->assign('monthoptions',$monthoptions);

$smarty->assign('task', $task);

$smarty->assign('search_query', $search_query);
$smarty->assign('maxpage', $page_vars[2]);
$smarty->assign('p', $page_vars[1]);
$smarty->assign('p_start', $page_vars[0]+1);
$smarty->assign('p_end', $page_vars[0]+count($employments));

$smarty->assign('search', $searched_fields);
$smarty->assign('total_employments',count($all_employments));

$smarty->assign('yearoptions',$yearoptions);
$smarty->assign('foroptions',$foroptions);
// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('employments', $employments);
$smarty->assign('rc_employment', $rc_employment);

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('result', $result);

include "footer.php";
?>