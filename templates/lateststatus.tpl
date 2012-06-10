{* BEGIN RECENT STATUS UPDATES*}
{*-------------------*}	
<div style="padding:10px 0 0 0;">
    {section name=statuses_loop loop=$statuses max=20}
      <div{if !$smarty.section.statuses_loop.first} style='padding-top:0px;'{/if}>
<table cellpadding='0' cellspacing='0'>
<tr>
<td>
<div>
<img src='{$statuses[statuses_loop]->user_photo('./images/nophoto.gif', TRUE)}' class='photo' width='40' height='40' border='0'></div>
</td>
<td valign='top' style='padding-left:5px; font-size:9pt;'>
<a href='{$url->url_create('profile', $statuses[statuses_loop]->user_info.user_username)}'>{$statuses[statuses_loop]->user_displayname}</a><br> {$statuses[statuses_loop]->user_info.user_status}
</td>
</tr>
</table>
</div>
<img class="right_block_hr" style="margin-top:5px; width:450px;" height="1px;" src="./images/hr_status.gif">
    {sectionelse}
      {lang_print id=1178}
    {/section}
</div>
{*-------------------*}	
{* END RECENT STATUS UPDATES*}