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

  <div class='profile_headline'>
    {lang_print id=11080101}
  </div>
  <div id="gmap_profile_address">{$gmap_location_address}</div>
  <br>
  {if $gmap_marker}
    {if $setting.setting_gmap_profile_embed == 1}
      {$rc_gmap->printHeaderJS()}
      {$rc_gmap->printMapJS()}
      {$rc_gmap->printOnLoad()}
      <div id="gmap_profile">{$rc_gmap->printMap()}</div>
    {/if}
    <a onclick="gmap_location_popup('{$url->url_base}gmap_location.php?user={$owner->user_info.user_username}'); return false;" href="#">{lang_print id=11080105}</a>
  {else}
    {lang_print id=11080103}
  {/if}

{/if}
