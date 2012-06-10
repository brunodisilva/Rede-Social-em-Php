<?
$page = "user_employment";
include "header.php";

$task = rc_toolkit::get_request('task','main');

$rc_validator = new rc_validator();
$rc_employment = new rc_employment($user->user_info[user_id]);

if($user->level_info[level_employment_allow] == 0) { header("Location: user_home.php"); exit(); }

$employment_new = array(
  'employment_id' => 'new',
  'employment_employer' => '',
  'employment_position' => '',
  'employment_description' => '',
  'employment_location' => '',
  'employment_is_current' => '0',
  'employment_from_month' => '0',
  'employment_from_year' => '0',
  'employment_to_month' => '0',
  'employment_to_year' => '0'
);


if ($task == 'dosave') {
  
  $employments = $_POST['employments'];
  //rc_toolkit::debug($employments);
  foreach ($employments as $eid=>$employment) {
    if (strlen($employment['employment_employer'])==0) {
      if ($eid == 'new') {
        $employment_new = $employment;
      }
      else {
        $rc_employment->delete($eid); 
      }
    }
    elseif ($eid == 'new') {
      $rc_employment->insert($employment);
    }
    else {
      $rc_employment->update($eid,$employment);
    }

  }
  
  $result = 11050714;
  
}


$employments = $rc_employment->get_employments();
$employments['new'] = $employment_new;

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
// ASSIGN VARIABLES AND INCLUDE FOOTER
$smarty->assign('employments', $employments);
$smarty->assign('rc_employment', $rc_employment);

$smarty->assign('is_error', $rc_validator->has_errors());
$smarty->assign('error_message', join(" ",$rc_validator->get_errors()));
$smarty->assign('result', $result);

include "footer.php";
?>