<html>
<head>
<title>Location :: {$owner->user_info.user_username}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="./templates/styles.css" title="stylesheet" type="text/css">  
{include file='header_gmap.tpl'}
</head>
<body>

{* BEGIN GMAP *}
{if $owner->usersetting_info.usersetting_permission_gmap != 0 && $owner->level_info.level_gmap_allow != 0}

<div id="gmap_location_pop">
  {if $gmap_marker}
      {$rc_gmap->printHeaderJS()}
      {$rc_gmap->printMapJS()}
      {$rc_gmap->printOnLoad()}
      <div id="gmap_location_pop_map">{$rc_gmap->printMap()}</div>
  {else}
    {lang_print id=11080103}
  {/if}
</div>

{/if}

</body>
</html>

