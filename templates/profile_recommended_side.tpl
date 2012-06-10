{if $owner->level_info.level_recommended_allow != 0 && $user->level_info.level_recommended_allow != 0 && $user->user_info.user_id != $owner->user_info.user_id}

  <table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 10px;'>
  <tr><td class='header'>{lang_print id=11140101}</td></tr>
  <tr>
  <td class='profile'>
    <div class="recommended_action"> 
      {if $recommended_has_vote}
        <img src='./images/icons/recommended_vote16.gif' border='0' class='icon'><a href="user_recommended_edit.php?user={$owner->user_info.user_username}">{lang_print id=11140102}</a>
      {else}
        <img src='./images/icons/recommended_vote16.gif' border='0' class='icon'><a href="user_recommended_add.php?user={$owner->user_info.user_username}">{lang_print id=11140103}</a>
      {/if}
    </div> 
  </td>
  </tr>
  </table>

{/if}