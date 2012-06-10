{include file='header.tpl'}


<table class='tabs' cellpadding='0' cellspacing='0'>
<tr>
<td class='tab0'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='user_recommended_settings.php'>{lang_print id=11140112}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab1' NOWRAP><a href='recommended_top_recommendees.php'>{lang_print id=11140110}</a></td>
<td class='tab'>&nbsp;</td>
<td class='tab2' NOWRAP><a href='recommended_top_recommenders.php'>{lang_print id=11140111}</a></td>
<td class='tab3'>&nbsp;</td>
</tr>
</table>

<img src='./images/icons/recommended_vote48.gif' border='0' class='icon_big'>
<div class='page_header'>{lang_print id=11140801}</div>
<div>{lang_print id=11140802}</a>
</div>
<br><br>

{if $total_entries == 0}

  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td class='error'><img src='./images/error.gif' border='0' class='icon'>{lang_print id=11140803}</td>
  </tr>
  </table>

{else}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='center'>
    {if $p != 1}<a href='recommended_top_recommendees.php?p={math equation='p-1' p=$p}'>&#171; {lang_print id=11140806}</a>{else}<font class='disabled'>&#171; {lang_print id=11140806}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=11140807} {$p_start} {lang_print id=11140808} {$total_entries} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=11140809} {$p_start}-{$p_end} {lang_print id=11140808} {$total_entries} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='recommended_top_recommendees.php?p={math equation='p+1' p=$p}'>{lang_print id=11140810} &#187;</a>{else}<font class='disabled'>{lang_print id=11140810} &#187;</font>{/if}
    </div>
    <br>
  {/if}


  {section name=user_loop loop=$recommendedvote_users}
    {* START NEW ROW *}
    {cycle name="startrow2" values="<table cellpadding='0' cellspacing='0' align='center'><tr>,,,,"}
    <td class='portal_member'>
      <a href='{$url->url_create('profile',$recommendedvote_users[user_loop]->user_username)}'>
        {$recommendedvote_users[user_loop]->user_displayname}
        <br><img src='{$recommendedvote_users[user_loop]->se_user->user_photo('./images/nophoto.gif')}' class='photo' width='{$misc->photo_size($recommendedvote_users[user_loop]->se_user->user_photo('./images/nophoto.gif'),'90','90','w')}' border='0'>
      </a>
        <br><a href='recommended_recommenders.php?user={$recommendedvote_users[user_loop]->user_username}'>{$recommendedvote_users[user_loop]->total_votes} {lang_print id=11140804}</a>
    </td>
    {* END ROW AFTER 5 RESULTS *}
    {if $smarty.section.user_loop.last == true}
      </tr></table>
    {else}
      {cycle name="endrow2" values=",,,,</tr></table>"}
    {/if}
  {/section}

  {* DISPLAY PAGINATION MENU IF APPLICABLE *}
  {if $maxpage > 1}
    <div class='center'>
    {if $p != 1}<a href='recommended_top_recommendees.php?p={math equation='p-1' p=$p}'>&#171; {lang_print id=11140806}</a>{else}<font class='disabled'>&#171; {lang_print id=11140806}</font>{/if}
    {if $p_start == $p_end}
      &nbsp;|&nbsp; {lang_print id=11140807} {$p_start} {lang_print id=11140808} {$total_entries} &nbsp;|&nbsp; 
    {else}
      &nbsp;|&nbsp; {lang_print id=11140809} {$p_start}-{$p_end} {lang_print id=11140808} {$total_entries} &nbsp;|&nbsp; 
    {/if}
    {if $p != $maxpage}<a href='recommended_top_recommendees.php?p={math equation='p+1' p=$p}'>{lang_print id=11140810} &#187;</a>{else}<font class='disabled'>{lang_print id=11140810} &#187;</font>{/if}
    </div>
    <br>
  {/if}  
  
{/if}

{include file='footer.tpl'}
