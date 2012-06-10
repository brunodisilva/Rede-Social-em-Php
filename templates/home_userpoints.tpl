  {* SHOW TOP USERS IF MORE THAN ZERO *}
  {if $userpoints_enable_topusers != 0}
  <div class='header'><a href='topusers.php'>{lang_print id=100016817}</a></div>
  <div class='portal_content'>
      {section name=up_topusers_loop loop=$up_topusers max=5}
        {* START NEW ROW *}
      {cycle name="startrowUPTU" values="<table cellpadding='0' cellspacing='0' align='center'><tr>,"}
      <td class='portal_member'>
        <a href='{$url->url_create('profile',$up_topusers[up_topusers_loop].user_username)}'>{$up_topusers[up_topusers_loop].user_displayname|truncate:15}<br><img src='{$up_topusers[up_topusers_loop].user_photo}' class='photo' width='60' height='60' border='0'></a>
        <br>{$up_topusers[up_topusers_loop].userpoints_totalearned} {lang_print id=100016818}</td>
        {* END ROW AFTER 5 RESULTS *}
        {if $smarty.section.up_topusers_loop.last == true}
          </tr></table>
        {else}
        {cycle name="endrowUPTU" values=",</tr></table>"}
        {/if}
    {sectionelse}
      {lang_print id=100016819}
      {/section}
  </div>
  <div class='portal_spacer'></div>
  {/if}
