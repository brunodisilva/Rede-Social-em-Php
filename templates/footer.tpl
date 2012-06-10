
{* $Id: footer.tpl 62 2009-02-18 02:59:27Z john $ *}

  {* SHOW PAGE BOTTOM ADVERTISEMENT BANNER *}
  {if $ads->ad_bottom != ""}
    <div class='ad_bottom' style='display: block; visibility: visible;'>
      {$ads->ad_bottom}
    </div>
  {/if}

{* END CONTENT CONTAINER *}
</div>


{* END BODY CONTAINER *}
</td>
</tr>
</table>


{* COPYRIGHT FOOTER *}
<div class='copyright'>

<div style="float:left; padding-top:2px;">
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

<div style="float:right; padding-top:5px;">
  <a href='help.php' class='copyright'>{lang_print id=752}</a> &nbsp;-&nbsp;
  <a href='help_tos.php' class='copyright'>{lang_print id=753}</a> &nbsp;-&nbsp;
  <a href='help_contact.php' class='copyright'>{lang_print id=754}</a>
  </div>

</div>


{* END CENTERED TABLE *}
</td>
</tr>
</table>


{* INCLUDE ANY FOOTER TEMPLATES NECESSARY *}
{hook_include name=footer}


</body>
</html>