<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* File:     compiler.userpointsrank.php
* Type:     compiler
* Name:     userpointsrank
* Purpose:  Output ranking calculation steps / levels
* Author:   Josh Goodman http://www.SocialEngineMods.net
* 
* -------------------------------------------------------------
*/
function smarty_compiler_userpointsrank($tag_arg, &$smarty) {
  global $database;

  // Load ranking levels from database and generate array 
  
  $rows = $database->database_query("SELECT * FROM se_semods_userpointranks ORDER BY userpointrank_amount");
  $userpoint_ranks = '';
  while($row = $database->database_fetch_assoc($rows)) {
    $userpoint_ranks .= '$userpoint_ranks[' . $row['userpointrank_amount'] . '] = \'' . $row['userpointrank_text'] . "';\n";
  }

    return <<< EOC
\n
$userpoint_ranks

\$userpoints = \$this->_tpl_vars['userpoints'] >= 0 ? \$this->_tpl_vars['user_points_totalearned'] : 0;

\$prev_step = 0;
\$prev_step_text = '';
\$userpoints_cntr = 1;
foreach(\$userpoint_ranks as \$key => \$value) {

  if((\$userpoints >= \$prev_step) && (\$userpoints < \$key)) {
    \$user_rank_string = \$prev_step_text;
    break;
  }

  if(\$userpoints_cntr++ >= count(\$userpoint_ranks)) {
    \$user_rank_string = \$value;
    break;
  }

  \$prev_step = \$key;
  \$prev_step_text = \$value;
}

echo \$user_rank_string;
EOC;
}
?> 