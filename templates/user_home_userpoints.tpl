  {* SHOW USER POINTS START 1/1 *}

  <div class='spacer10'></div>
  <table cellpadding='0' cellspacing='0' class='portal_table' align='center' width='100%'>
  <tr><td class='header'>{lang_print id=100016732}</td></tr>
  <tr>
  <td class='home_box'>

    <table cellpadding='0' cellspacing='0'>
    <tr>
    <td valign='top'>&nbsp;&nbsp;</td>
    <td>
	  {if $userpoints_enable_topusers}
		{lang_print id=100016733} <a href="topusers.php"> <span style="font-weight:bold">{if $user_points_totalearned != 0 } {$user_rank} {else} {lang_print id=100016738} {/if}</span> </a> <br><br>
      {/if}

	  {if $userpoints_enable_pointrank}
		{lang_print id=100016737} <span style="font-weight:bold">{include file='user_points_staticrank.tpl'}</span> <br><br>
	  {/if}

      {lang_print id=100016734} <a href="user_vault.php"> <span style="font-weight:bold" id="voter_points_count">{$user_points}</span> {lang_print id=100016735}</a> <br><br>
      {lang_print id=100016736} <a href="user_vault.php"> <span style="font-weight:bold" id="voter_points_count">{$user_points_totalearned}</span> {lang_print id=100016735}</a> <br><br>

      <a href="user_points_offers.php">{lang_print id=100016739}</a>
      <span style="pading-left: 4px; padding-right: 4px; color: #CCC"> | </span>
      <a href="user_points_shop.php">{lang_print id=100016740}</a>
	
    </td>
    </tr>
    </table>

  </td>
  </tr>
  </table>

  {* SHOW USER POINTS END 1/1 *}
