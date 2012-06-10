<?php /* Smarty version 2.6.14, created on 2012-06-10 06:36:55
         compiled from iheader.tpl */
?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'iheader.tpl', 119, false),array('modifier', 'count', 'iheader.tpl', 123, false),)), $this);
?><?php
SELanguage::_preload_multi(660,675,30,37,29,266,28,685,709,689,690,975,691,692,693);
SELanguage::load();
?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header_global.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 
 echo '
<style type="text/css">
body { background-image:url(./images/index_bg.gif); background-repeat:repeat-x; }
</style>
'; 
 if (@SE_DEBUG && $this->_tpl_vars['admin']->admin_exists): 
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header_debug.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 
 endif; ?>

<div id="smoothbox_container"></div>

<table cellpadding='0' cellspacing='0' class='body' align='center'>
<tr>
<td>



<div class="top_bar_index">

  <table cellpadding='0' cellspacing='0' style='width:965px; padding-top:1px; height:29.5px;' align='center'>
<tr>
    <td style="background-image:url(./images/top_left_index.gif); height:85.5px; padding-left:5px;" align='left' width="4px">&nbsp;</td>
    <td width="75"><a href='./'><img style="vertical-align:middle" src='./images/logo_index.gif' border='0'></a></td>
	
	
	

	
	
	<td style="padding-left:10px;" align="right"; width="auto">
<div style="text-align:right; float:right; color:#98a9ca; margin-top:20px;">

        <form action='login.php' method='post'>
        <input style="vertical-align:middle;" type='checkbox' name='persistent' value='1' id='rememberme'> <label style="color:#98a9ca; margin-right:70px; vertical-align:middle; cursor:pointer;" for='rememberme'><?php echo SELanguage::_get(660); ?></label>  <a style="color:#98a9ca; margin-right:65px; " href="lostpass.php"><?php echo SELanguage::_get(675); ?></a>
		<br>
<input type='text' class='text_index' name='email' size='23' maxlength='100' value='<?php echo $this->_tpl_vars['prev_email']; ?>
'>
<input type='password' class='text_index' name='password' size='23' maxlength='100'>

        <input type='submit' class='button_index' value='<?php echo SELanguage::_get(30); ?>'>&nbsp;
        <NOSCRIPT><input type='hidden' name='javascript_disabled' value='1'></NOSCRIPT>
        <input type='hidden' name='task' value='dologin'>
        <input type='hidden' name='ip' value='<?php echo $this->_tpl_vars['ip']; ?>
'>
        </form>


</div>

	</td>

	    <td style="background-image:url(./images/top_right_index.gif); height:85.5px; padding-left:5px;" align='left' width="13px">&nbsp;</td>
</tr>
</table>
</div>
</td>
  </tr>
  </table>


<div style="height:380px; width:960px; margin-right:auto; margin-left:auto;">

	<div style="width:555px; padding-top:10px; float:left;">
			<div style="float:left; width:500px; height:75px; padding-left:10px; font-size:20px; text-align:left; font-weight:bold; color:#203360;">Facecook helps you connect and share with the people in your life.
			</div>
			<div style="float:left; background-image:url(./images/home_pic.gif); width:550px; height:200px;">
			</div>
	</div>
	
	<div style="width:375px; float:right; padding-top:20px; text-align:left;">
<font style="font-size:20px; text-align:left; font-weight:bold; color:#203360;">Signup</font><br><br>
<font color="#203360;" style="font-size:20px; text-align:left; color:#203360;">It's free and anyone can join</font><br>&nbsp;
<br>





  <form action='signup.php' method='POST'>
  <table cellpadding='0' cellspacing='0'>
  <tr>
  <td class='form1' width='100'><?php echo SELanguage::_get(37); ?></td>
  <td class='form2'>
    <input name='signup_email' type='text' class='text' maxlength='70' value='<?php echo $this->_tpl_vars['signup_email']; ?>
' style="width:230px;" />
  </td>
  </tr>
  <?php if ($this->_tpl_vars['setting']['setting_signup_randpass'] == 0): ?>
    <tr>
    <td class='form1'><?php echo SELanguage::_get(29); ?>:</td>
    <td class='form2'>
      <input name='signup_password' type='password' class='text' maxlength='50' value='<?php echo $this->_tpl_vars['signup_password']; ?>
' style="width:230px;" />
    </td>
    </tr>
    <tr>
    <td class='form1'><?php echo SELanguage::_get(266); ?>:</td>
    <td class='form2'>
      <input name='signup_password2' type='password' class='text' maxlength='50' value='<?php echo $this->_tpl_vars['signup_password2']; ?>
' style="width:230px;" />
    </td>
    </tr>
  <?php else: ?>
    <input type='hidden' name='signup_password' value=''>
    <input type='hidden' name='signup_password2' value=''>
  <?php endif; ?>
  </table>

  <table cellpadding='0' cellspacing='0'>
  <?php if ($this->_tpl_vars['setting']['setting_username']): ?>
    <tr>
    <td class='form1'><?php echo SELanguage::_get(28); ?>:</td>
    <td class='form2'>
      <input name='signup_username' type='text' class='text' maxlength='50' value='<?php echo $this->_tpl_vars['signup_username']; ?>
' style="width:230px;" />
      <?php ob_start(); 
 echo SELanguage::_get(685); 
 $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('tip', ob_get_contents());ob_end_clean(); ?>
      <img src='./images/icons/tip.gif' border='0' class='Tips1' title='<?php echo ((is_array($_tmp=$this->_tpl_vars['tip'])) ? $this->_run_mod_handler('replace', true, $_tmp, "'", "&#039;") : smarty_modifier_replace($_tmp, "'", "&#039;")); ?>
'>
    </td>
    </tr>
  <?php endif; ?>
  <?php if (count($this->_tpl_vars['cats']) > 1): ?>
    <tr>
    <td class='form1'><?php echo SELanguage::_get(709); ?>:</td>
    <td class='form2'>
      <select name='signup_cat'>
      <?php unset($this->_sections['cat_loop']);
$this->_sections['cat_loop']['name'] = 'cat_loop';
$this->_sections['cat_loop']['loop'] = is_array($_loop=$this->_tpl_vars['cats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat_loop']['show'] = true;
$this->_sections['cat_loop']['max'] = $this->_sections['cat_loop']['loop'];
$this->_sections['cat_loop']['step'] = 1;
$this->_sections['cat_loop']['start'] = $this->_sections['cat_loop']['step'] > 0 ? 0 : $this->_sections['cat_loop']['loop']-1;
if ($this->_sections['cat_loop']['show']) {
    $this->_sections['cat_loop']['total'] = $this->_sections['cat_loop']['loop'];
    if ($this->_sections['cat_loop']['total'] == 0)
        $this->_sections['cat_loop']['show'] = false;
} else
    $this->_sections['cat_loop']['total'] = 0;
if ($this->_sections['cat_loop']['show']):

            for ($this->_sections['cat_loop']['index'] = $this->_sections['cat_loop']['start'], $this->_sections['cat_loop']['iteration'] = 1;
                 $this->_sections['cat_loop']['iteration'] <= $this->_sections['cat_loop']['total'];
                 $this->_sections['cat_loop']['index'] += $this->_sections['cat_loop']['step'], $this->_sections['cat_loop']['iteration']++):
$this->_sections['cat_loop']['rownum'] = $this->_sections['cat_loop']['iteration'];
$this->_sections['cat_loop']['index_prev'] = $this->_sections['cat_loop']['index'] - $this->_sections['cat_loop']['step'];
$this->_sections['cat_loop']['index_next'] = $this->_sections['cat_loop']['index'] + $this->_sections['cat_loop']['step'];
$this->_sections['cat_loop']['first']      = ($this->_sections['cat_loop']['iteration'] == 1);
$this->_sections['cat_loop']['last']       = ($this->_sections['cat_loop']['iteration'] == $this->_sections['cat_loop']['total']);
?>
	<option value='<?php echo $this->_tpl_vars['cats'][$this->_sections['cat_loop']['index']]['cat_id']; ?>
'<?php if ($this->_tpl_vars['signup_cat'] == $this->_tpl_vars['cats'][$this->_sections['cat_loop']['index']]['cat_id']): ?> selected='selected'<?php endif; ?>><?php echo SELanguage::_get($this->_tpl_vars['cats'][$this->_sections['cat_loop']['index']]['cat_title']); ?></option>
      <?php endfor; endif; ?>
      </select>
    </td>
    </tr>
  <?php endif; ?>
  <tr>
  <td class='form1' width='100'></td>
  <td class='form2'>

  </td>
  </tr>
  </table>

  <?php if ($this->_tpl_vars['setting']['setting_signup_code'] == 1 || $this->_tpl_vars['setting']['setting_signup_tos'] == 1 || $this->_tpl_vars['setting']['setting_signup_invite'] != 0): ?>
    <table cellpadding='0' cellspacing='0'>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['setting']['setting_signup_invite'] != 0): ?>
    <tr>
    <td class='form1' width='100'><?php echo SELanguage::_get(689); ?></td>
    <td class='form2'><input type='text' name='signup_invite' value='<?php echo $this->_tpl_vars['signup_invite']; ?>
' class='text' size='10' maxlength='10''></td>
    </tr>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['setting']['setting_signup_code'] == 1): ?>
    <tr>
    <td class='form1' width='100'><?php echo SELanguage::_get(690); ?></td>
    <td class='form2'>
      <table cellpadding='0' cellspacing='0'>
      <tr>
      <td><input type='text' name='signup_secure' class='text' size='6' maxlength='10'>&nbsp;</td>
      <td>
        <table cellpadding='0' cellspacing='0'>
        <tr>
        <td align='center'><img src='./images/secure.php' id='secure_image' border='0' height='20' width='67' class='signup_code'><br><a href="javascript:void(0);" onClick="javascript:$('secure_image').src = $('secure_image').src + '?' + (new Date()).getTime();"><?php echo SELanguage::_get(975); ?></a></td>
        <td><?php ob_start(); 
 echo SELanguage::_get(691); 
 $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('tip', ob_get_contents());ob_end_clean(); ?><img src='./images/icons/tip.gif' border='0' class='Tips1' title='<?php echo ((is_array($_tmp=$this->_tpl_vars['tip'])) ? $this->_run_mod_handler('replace', true, $_tmp, "'", "&#039;") : smarty_modifier_replace($_tmp, "'", "&#039;")); ?>
'></td>
        </tr>
        </table>
      </td>
      </tr>
      </table>
    </td>
    </tr>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['setting']['setting_signup_tos'] == 1): ?>
    <tr>
    <td class='form1' width='100'>&nbsp;</td>
    <td class='form2'><input type='checkbox' name='signup_agree' id='tos' value='1'<?php if ($this->_tpl_vars['signup_agree'] == 1): ?> CHECKED<?php endif; ?>><label for='tos'> <?php echo SELanguage::_get(692); ?></label></td>
    </tr>
  <?php endif; ?>

  <tr></tr>
  <tr>
  <td class='form1'>&nbsp;</td>
  <td class='form2'><input type='submit' class='button_signup' value='<?php echo SELanguage::_get(693); ?>'></td>
  </tr>
  </table>
  <input type='hidden' name='task' value='<?php echo $this->_tpl_vars['next_task']; ?>
'>
  <input type='hidden' name='step' value='<?php echo $this->_tpl_vars['step']; ?>
'>
  </form>

	</div>

</div>
  
  
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'ifooter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>