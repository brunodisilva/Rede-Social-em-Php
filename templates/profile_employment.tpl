{if $owner->level_info.level_employment_allow != 0 AND ($total_employments > 0 OR $owner->user_info.user_username == $user->user_info.user_username) }
  <table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 10px;'>
  <tr><td class='header'>
   {lang_print id=11050101} ({$total_employments})
  </td></tr>
  <tr>
  <td class='profile'>
  {foreach from=$employments item=employment}
    <h3 class="employment_header">{$employment.search_employment_employer}</h3>
    <table cellpadding='0' cellspacing='0' class="employment">
      {if $employment.search_employment_position != ""}<tr><td width="80">{lang_print id=11050105}</td><td>{$employment.search_employment_position}</td></tr>{/if}
      {if $employment.time_period != "" }
        <tr><td width="80">{lang_print id=11050108}</td>
        <td>{$employment.time_period}</td></tr>
      {/if}
      {if $employment.search_employment_location != ""}<tr><td width="80">{lang_print id=11050107}</td><td>{$employment.search_employment_location}</td></tr>{/if}
      {if $employment.search_employment_description != ""}<tr><td width="80">{lang_print id=11050106}</td><td>{$employment.search_employment_description|nl2br}</td></tr>{/if}
    </table>
  {/foreach}
  {if $owner->user_info.user_username == $user->user_info.user_username}
    <div><img src='./images/icons/employment16.gif' border='0' class='icon'><a href="user_employment.php">{lang_print id=11050102}</a></div>
    {/if}
  </td>
  </tr>
  </table>
{/if}