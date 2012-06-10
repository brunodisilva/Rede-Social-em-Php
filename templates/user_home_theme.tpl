{if $theme_switcher_options}
  <div class='spacer10'></div>
  <div class='header'>{lang_print id=11120721}</div>
  <div class='network_content'>
	  <form action='theme_switch.php' method='POST'>
	  <select name='theme_id' onchange="this.form.submit();">
	    <option value='0'>{lang_print id=11120102}</option>
	    {foreach from=$theme_switcher_options key=theme_id item=theme_name}
	      <option value='{$theme_id}' {if $user->user_info.user_theme_id == $theme_id}selected='selected'{/if}>{$theme_name}</option>
	    {/foreach}
	  </select>
	  </form>
  </div>
{/if}
