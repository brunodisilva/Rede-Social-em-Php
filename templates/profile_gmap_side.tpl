{* BEGIN GMAP *}
{if $owner->level_info.level_gmap_allow != 0 && $owner->usersetting_info.usersetting_permission_gmap != 0}

{literal}
<script type="text/javascript" language="javascript">
function gmap_location_popup(url) {
  window.open(url, "gmap_location_popup", 
  "status = 1, height = 430, width = 540, resizable = 0, status = 0, location = 0, menubar = 0, toolbar = 0, top = 10, left = 10" )
}
</script>
{/literal}

  <table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 10px;'>
  <tr><td class='header'>
    {lang_print id=11080101}
  </td></tr>
  <tr>
  <td class='profile'>
    <div id="gmap_profile_address">{$gmap_location_address}</div>
    {if $gmap_marker}
      <a onclick="gmap_location_popup('{$url->url_base}gmap_location.php?user={$owner->user_info.user_username}'); return false;" href="#">{lang_print id=11080105}</a>
    {else}
      {lang_print id=11080103}
    {/if}
  </td>
  </tr>
  </table>

{/if}
