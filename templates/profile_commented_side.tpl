{if $setting.setting_permission_commented != 0 && $owner->level_info.level_commented_allow != 0 }
  <table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 10px;'>
  <tr><td class='header'>{lang_print id=11130101}</td></tr>
  <tr>
  <td class='profile'>
    <table cellspacing="0" cellpadding="0" class="commented_sync">
      <tr><td><img src='images/icons/commented16.gif' class='icon' /></td>
      <td><a href='commented.php?user={$owner->user_info.user_username}'>
      {if $commented_total_users > 0}{lang_print id=11130102} {$commented_total_users} {lang_print id=11130103}{else}{lang_print id=11130104}{/if}
      </a></td></tr>
    </table>
  </td>
  </tr>
  </table>
{/if}