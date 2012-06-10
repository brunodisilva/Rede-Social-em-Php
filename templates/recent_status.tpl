  {* RECENT STATUS UPDATES *}
    {section name=statuses_loop loop=$statuses max=20}
      <div{if !$smarty.section.statuses_loop.first} style='padding-top: 7px;'{/if}>
<table cellpadding='0' cellspacing='0'>
<tr>
<td>
<div style='border: 1px solid #e0e0e0; padding:2px;'>
<img src='{$statuses[statuses_loop]->user_photo('./images/nophoto.gif', TRUE)}' class='photo' width='40' height='40' border='0'></div>
</td>
<td valign='top' style='padding-left:2px;'>
<a href='{$url->url_create('profile', $statuses[statuses_loop]->user_info.user_username)}'>{$statuses[statuses_loop]->user_displayname}</a> {$statuses[statuses_loop]->user_info.user_status}
</td>
</tr>
</table>
<div style='margin:5px; border-bottom:1px dotted #e0e0e0;'></div>
    {sectionelse}
      {lang_print id=1178}
    {/section}
  