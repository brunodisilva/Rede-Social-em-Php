{* BEGIN USERPOINTS *}
{if ($owner->level_info.level_userpoints_allow != 0) && ($owner->user_info.user_userpoints_allowed != 0) }

  <table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 10px;'>
  <tr><td class='header'> {lang_print id=100016725}  </td></tr>
  <tr>
  <td class='profile'>

    <table cellpadding='0' cellspacing='0'>
	  {if $userpoints_enable_topusers}
      <tr> <td valign='top'> {lang_print id=100016726} </td><td style="padding-left: 5px"> <a href="topusers.php"> <span style="font-weight:bold">{if $user_points_totalearned != 0 } {$user_rank} {else} {lang_print id=100016731} {/if}</span> </a> <br> </td></tr>
	  {/if}
	  {if $userpoints_enable_pointrank}
	  <tr> <td valign='top'> {lang_print id=100016730} </td><td style="padding-left: 5px"> <span style="font-weight:bold">{include file='user_points_staticrank.tpl'}</span> <br> </td></tr>
	  {/if}
	  <tr> <td valign='top'> {lang_print id=100016729} </td><td style="padding-left: 5px"> <span style="font-weight:bold" id="voter_points_count">{$user_points_totalearned}</span> {lang_print id=100016728} <br> </td></tr>
    </table>

  </td>
  </tr>
  </table>

{/if}
