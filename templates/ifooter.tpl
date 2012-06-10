{* COPYRIGHT FOOTER *}
<div class='copyright2'>

<div style="float:left;">
  {lang_print id=1175} &copy; {$smarty.now|date_format:'%Y'}
  {if $setting.setting_lang_anonymous == 1 && $lang_packlist|@count != 0}
    &nbsp;&nbsp;
    {if $smarty.server.QUERY_STRING|strpos:"&lang_id=" !== FALSE}{assign var="pos" value=$smarty.server.QUERY_STRING|strstr:"&lang_id="}{assign var="query_string" value=$smarty.server.QUERY_STRING|replace:$pos:""}{else}{assign var="query_string" value=$smarty.server.QUERY_STRING}{/if}
    <select class='small2' name='user_language_id' onchange="window.location.href='{$smarty.server.PHP_SELF}?{$query_string}&lang_id='+this.options[this.selectedIndex].value;">
      {section name=lang_loop loop=$lang_packlist}
        <option value='{$lang_packlist[lang_loop].language_id}'{if $lang_packlist[lang_loop].language_id == $global_language} selected='selected'{/if}>{$lang_packlist[lang_loop].language_name}</option>
       {/section}
    </select>
  {/if}
</div>

<div style="float:right; ">
  <a href='help.php' class='copyright'>{lang_print id=752}</a> &nbsp;-&nbsp;
  <a href='help_tos.php' class='copyright'>{lang_print id=753}</a> &nbsp;-&nbsp;
  <a href='help_contact.php' class='copyright'>{lang_print id=754}</a>
  </div>
</div>