<?php /* Smarty version 2.6.14, created on 2012-06-10 06:36:55
         compiled from ifooter.tpl */
?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'ifooter.tpl', 5, false),array('modifier', 'count', 'ifooter.tpl', 6, false),array('modifier', 'strpos', 'ifooter.tpl', 8, false),array('modifier', 'strstr', 'ifooter.tpl', 8, false),array('modifier', 'replace', 'ifooter.tpl', 8, false),)), $this);
?><?php
SELanguage::_preload_multi(1175,752,753,754);
SELanguage::load();
?><div class='copyright2'>

<div style="float:left;">
  <?php echo SELanguage::_get(1175); ?> &copy; <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y') : smarty_modifier_date_format($_tmp, '%Y')); ?>

  <?php if ($this->_tpl_vars['setting']['setting_lang_anonymous'] == 1 && count($this->_tpl_vars['lang_packlist']) != 0): ?>
    &nbsp;&nbsp;
    <?php if (((is_array($_tmp=$_SERVER['QUERY_STRING'])) ? $this->_run_mod_handler('strpos', true, $_tmp, "&lang_id=") : strpos($_tmp, "&lang_id=")) !== FALSE): 
 $this->assign('pos', ((is_array($_tmp=$_SERVER['QUERY_STRING'])) ? $this->_run_mod_handler('strstr', true, $_tmp, "&lang_id=") : strstr($_tmp, "&lang_id="))); 
 $this->assign('query_string', ((is_array($_tmp=$_SERVER['QUERY_STRING'])) ? $this->_run_mod_handler('replace', true, $_tmp, $this->_tpl_vars['pos'], "") : smarty_modifier_replace($_tmp, $this->_tpl_vars['pos'], ""))); 
 else: 
 $this->assign('query_string', $_SERVER['QUERY_STRING']); 
 endif; ?>
    <select class='small2' name='user_language_id' onchange="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>
?<?php echo $this->_tpl_vars['query_string']; ?>
&lang_id='+this.options[this.selectedIndex].value;">
      <?php unset($this->_sections['lang_loop']);
$this->_sections['lang_loop']['name'] = 'lang_loop';
$this->_sections['lang_loop']['loop'] = is_array($_loop=$this->_tpl_vars['lang_packlist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['lang_loop']['show'] = true;
$this->_sections['lang_loop']['max'] = $this->_sections['lang_loop']['loop'];
$this->_sections['lang_loop']['step'] = 1;
$this->_sections['lang_loop']['start'] = $this->_sections['lang_loop']['step'] > 0 ? 0 : $this->_sections['lang_loop']['loop']-1;
if ($this->_sections['lang_loop']['show']) {
    $this->_sections['lang_loop']['total'] = $this->_sections['lang_loop']['loop'];
    if ($this->_sections['lang_loop']['total'] == 0)
        $this->_sections['lang_loop']['show'] = false;
} else
    $this->_sections['lang_loop']['total'] = 0;
if ($this->_sections['lang_loop']['show']):

            for ($this->_sections['lang_loop']['index'] = $this->_sections['lang_loop']['start'], $this->_sections['lang_loop']['iteration'] = 1;
                 $this->_sections['lang_loop']['iteration'] <= $this->_sections['lang_loop']['total'];
                 $this->_sections['lang_loop']['index'] += $this->_sections['lang_loop']['step'], $this->_sections['lang_loop']['iteration']++):
$this->_sections['lang_loop']['rownum'] = $this->_sections['lang_loop']['iteration'];
$this->_sections['lang_loop']['index_prev'] = $this->_sections['lang_loop']['index'] - $this->_sections['lang_loop']['step'];
$this->_sections['lang_loop']['index_next'] = $this->_sections['lang_loop']['index'] + $this->_sections['lang_loop']['step'];
$this->_sections['lang_loop']['first']      = ($this->_sections['lang_loop']['iteration'] == 1);
$this->_sections['lang_loop']['last']       = ($this->_sections['lang_loop']['iteration'] == $this->_sections['lang_loop']['total']);
?>
        <option value='<?php echo $this->_tpl_vars['lang_packlist'][$this->_sections['lang_loop']['index']]['language_id']; ?>
'<?php if ($this->_tpl_vars['lang_packlist'][$this->_sections['lang_loop']['index']]['language_id'] == $this->_tpl_vars['global_language']): ?> selected='selected'<?php endif; ?>><?php echo $this->_tpl_vars['lang_packlist'][$this->_sections['lang_loop']['index']]['language_name']; ?>
</option>
       <?php endfor; endif; ?>
    </select>
  <?php endif; ?>
</div>

<div style="float:right; ">
  <a href='help.php' class='copyright'><?php echo SELanguage::_get(752); ?></a> &nbsp;-&nbsp;
  <a href='help_tos.php' class='copyright'><?php echo SELanguage::_get(753); ?></a> &nbsp;-&nbsp;
  <a href='help_contact.php' class='copyright'><?php echo SELanguage::_get(754); ?></a>
  </div>
</div>