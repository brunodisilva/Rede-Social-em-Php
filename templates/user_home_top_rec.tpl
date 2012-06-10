{* TOP RECOMMENDATION*}
    <h4>Recommended SupremeEdition USERS</h4>
    
    {section name=user_loop loop=$recommendedvote_users max=5}
    {* START NEW ROW *}
    {cycle name="startrow2" values="<table cellpadding='0' cellspacing='0' align='center'>,,,,<tr>"}
    <td class='portal_member'>
      <a href='{$url->url_create('profile',$recommendedvote_users[user_loop]->user_username)}'>
        {$recommendedvote_users[user_loop]->user_displayname}
        <br><img src='{$recommendedvote_users[user_loop]->se_user->user_photo('./images/nophoto.gif')}' class='photo' height='100' width='{$misc->photo_size($recommendedvote_users[user_loop]->se_user->user_photo('./images/nophoto.gif'),'100','100')}' border='0'>
      </a>
        <br><a href='recommended_recommenders.php?user={$recommendedvote_users[user_loop]->user_username}'>{$recommendedvote_users[user_loop]->total_votes} {lang_print id=11140804}</a>
    </td>
    {* END ROW AFTER 5 RESULTS *}
    {if $smarty.section.user_loop.last == true}
      </tr></table>
    {else}
    
    {/if}
{/section}
