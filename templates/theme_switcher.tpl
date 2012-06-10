{if $theme_switcher_options}
  <form action='theme_switch.php' method='POST'>
  {lang_print id=11120101}
  <select name='theme_id' onchange="this.form.submit();">
    <option value='0'>{lang_print id=11120102}</option>
    {foreach from=$theme_switcher_options key=theme_id item=theme_name}
      <option value='{$theme_id}' {if $user->user_info.user_theme_id == $theme_id}selected='selected'{/if}>{$theme_name}</option>
    {/foreach}
  </select>
  </form>
{/if}