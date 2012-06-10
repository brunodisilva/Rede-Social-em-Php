{include file='admin_header.tpl'}

<script>
  var lang_dlg_title1 = "{lang_print id=100011043}";
  var lang_dlg_text1 = "{lang_print id=100011044}";
  var row_id = 0;
</script>

{literal}
<link rel="stylesheet" href="../templates/styles_semods.css" title="stylesheet" type="text/css">  

<script type="text/javascript" src="../include/js/semods.js"></script>
<script type="text/javascript" src="../include/js/semods.controls.dialog.js"></script>

<script>

function email_delete(delete_id) {
  row_id = delete_id;
  new SEMods.Controls.Dialog( { title : lang_dlg_title1, text : lang_dlg_text1, width : "300px", height : "150px", onYes : email_delete_confirmed } );
}

function email_delete_confirmed() {
  var asyncform = document.getElementById('asyncform');
  document.getElementById('asyncform_task').value = "delete";
  document.getElementById('asyncform_item_id').value = row_id;
  asyncform.submit();
}
</script>
{/literal}

<h2>{lang_print id=100011045}</h2>
{lang_print id=100011046}

<br><br>

<table cellpadding='0' cellspacing='0' align='center'>
<tr>
<td align='center'>
<div class='box'>
<table cellpadding='0' cellspacing='0' xalign='center'>
<tr><form action='admin_emailer_viewemails.php' method='GET'>
<td>{lang_print id=100011047}<br><input type='text' class='text' name='f_email' value='{$f_email}' size='50' maxlength='100'>&nbsp;</td>
<td>{lang_print id=100011048}<br><select class='text' name='f_type'><option value=-1>{lang_print id=100011049}</option>{foreach from=$queued_email_types item=type}<option value='{$type.type_id}'{if $f_type == $type.type_id} SELECTED{/if}>{$type.type_name}</option>{/foreach}</select>&nbsp;</td>
<td valign='bottom'><input type='submit' class='button' value='{lang_print id=100011050}'></td>
<input type='hidden' name='s' value='{$s}'>
</tr>
</table>
</form>
</div>
</td></tr></table>

<br>

{if $total_items == 0}

<table width="400" cellspacing="0" cellpadding="0" align="center">
<tr>
  <td align="center">
  <div class="box"><b>{lang_print id=100011051}</b></div>
</td></tr>
</table>

{else}

  <div class='pages'>{$total_items} {lang_print id=100011052} &nbsp;|&nbsp; {lang_print id=100011053} {section name=page_loop loop=$pages}{if $pages[page_loop].link == '1'}{$pages[page_loop].page}{else}<a href='admin_emailer_viewemails.php?s={$s}&p={$pages[page_loop].page}&f_user={$f_user}&f_title={$f_title}&f_status={$f_status}&f_paid={$f_paid}'>{$pages[page_loop].page}</a>{/if} {/section}</div>

<table cellpadding='0' cellspacing='0' class='list' width='100%'>
<tr>
<td class='header' nowrap='nowrap'>{lang_print id=100011054}&nbsp;</td>
<td class='header' width='100%'>{lang_print id=100011055}&nbsp;</td>
<td class='header' nowrap='nowrap'>{lang_print id=100011056}&nbsp;</td>
<td class='header' nowrap='nowrap'>{lang_print id=100011057}&nbsp;</td>
<td class='header' nowrap='nowrap'>{lang_print id=100011058}&nbsp;</td>
<td class='header' nowrap='nowrap'>{lang_print id=100011059}&nbsp;</td>
</tr>

  {foreach from=$items item=item}
    
    <tr class='{cycle values="background1,background2"}'>
    <td class='item' nowrap='nowrap'>{$item.to_email}</td>
    <td class='item' width='100%'>{$item.subject|truncate:50:"...":true}</a></td>
    <td class='item' nowrap='nowrap'>{$item.type}</a></td>
    <td class='item' nowrap='nowrap'>{$item.attempts}</a></td>
    <td class='item' nowrap='nowrap'>{if $item.last_attempt == 0}{lang_print id=100011060}{else}{$item.last_attempt}{/if}</a></td>
    <td class='item' nowrap='nowrap'>
      <a href='javascript:email_delete({$item.id})'>{lang_print id=100011061}</a>
    </td>
  {/foreach}

</table>

</td>
</tr>
</table>
{/if}

<form method="POST" id="asyncform" name="asyncform" action="admin_emailer_viewemails.php">
  <input type="hidden" id="asyncform_task" name="task">
  <input type="hidden" id="asyncform_item_id" name="row_id">

  <input type="hidden" name="f_email" value="{$f_user}">
  <input type="hidden" name="f_type" value="{$f_title}">
  <input type="hidden" name="p" value="{$p}">
  <input type="hidden" name="s" value="{$s}">

</form>

{include file='admin_footer.tpl'}