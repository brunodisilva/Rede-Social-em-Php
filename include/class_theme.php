<?

include_once('class_radcodes.php');

class rc_theme extends rc_model 
{
  var $table = 'se_themes';
  var $pk = 'theme_id';
  
  function get_default()
  {
    return $this->get_record_by_criteria("theme_default='1'");
  }
  
  function set_default($theme_id)
  {
    $new_default = $this->get_record($theme_id);
    if ($new_default) {
      $cur_default = $this->get_default();
      if ($cur_default) {
        $this->update($cur_default[$this->pk],array('theme_default'=>0));
      }
      return $this->update($new_default[$this->pk],array('theme_default'=>1));
    }
    return false;
  }
  
  function insert($data)
  {
    $default = $this->get_default();
    if (!$default) {
      $data['theme_default'] = 1;
    }
    return parent::insert($data);
  }
  
  function delete($id)
  {
    $this->db->database_query("UPDATE se_subnets SET subnet_theme_id='0' WHERE subnet_theme_id='$id'");
    return parent::delete($id);
  }
  

  function get_record_for_subnet($subnet_id)
  {
    $res = $this->db->database_query("SELECT * FROM se_subnets WHERE subnet_id='$subnet_id'");
    $subnet = $this->db->database_fetch_assoc($res);
    if ($subnet) {
      return $this->get_record($subnet['subnet_theme_id']);
    }
    return null;
  }

  function get_record_for_level($level_id)
  {
    $res = $this->db->database_query("SELECT * FROM se_levels WHERE level_id='$level_id'");
    $level = $this->db->database_fetch_assoc($res);
    if ($level) {
      return $this->get_record($level['level_theme_id']);
    }
    return null;
  }
  
  function get_subnet_theme_id($subnet_id)
  {
  	$res = $this->db->database_query("SELECT * FROM se_subnets WHERE subnet_id='$subnet_id'");
    $subnet = $this->db->database_fetch_assoc($res);
    $theme_id = $subnet['subnet_theme_id'];
    return $theme_id;
  }
  
  function get_level_theme_id($level_id)
  {
    $res = $this->db->database_query("SELECT * FROM se_levels WHERE level_id='$level_id'");
    $level = $this->db->database_fetch_assoc($res);
    $theme_id = $level['level_theme_id'];
    return $theme_id;
  }

  function get_dynamic_theme_id($user)
  {
  	global $setting;
  	
  	$type = $setting['setting_theme_type'];
  	//rc_toolkit::debug($type, "({$setting['setting_theme_user_overwrite']} != 0 && {$user->user_info['user_theme_id']} != 0)");
  	//rc_toolkit::debug(($setting['setting_theme_user_overwrite'] != 0 && $user->user_info['user_theme_id'] != 0),"testing");
  	if ($type == 3 || ($setting['setting_theme_user_overwrite'] != 0 && $user->user_info['user_theme_id'] != 0)) {
  		$theme_id = $user->user_info['user_theme_id'];
  		//rc_toolkit::debug($theme_id,'im here');
  	}
  	elseif ($type == 1) {
  		$theme_id = $this->get_subnet_theme_id($user->user_info['user_subnet_id']);
  	}
  	elseif ($type == 2) {
      $theme_id = $this->get_level_theme_id($user->user_info['user_level_id']);
  	}
  	
  	return $theme_id;
  }
  
  
  function get_block_column($block)
  {
    if (isset($block['themeblock_id'])) {
      $block = $block['themeblock_id'];
    }
    return is_numeric($block) ? "theme_block_$block" : null;
  } 
  
  function get_cached_theme($theme_id)
  {
  	static $themes;
  	
  	if (!isset($themes[$theme_id])) {
  		if ($theme_id > 0) {
  			$themes[$theme_id] = $this->get_record($theme_id);
  		}
  		else {
  			$themes[$theme_id] = $this->get_default();
  		}
  	}
  	return $themes[$theme_id];
  }
  
  function get_theme_switcher_options()
  {
  	$opts = array();
  	$themes = $this->FindRecordsByCriteria("WHERE theme_status = '1' ORDER BY theme_name ASC", true);
  	foreach ($themes as $v) {
  		$opts[$v->theme_id] = $v->theme_name;
  	}
  	return $opts;
  }
  
}

class rc_themeblock extends rc_model
{
  var $table = 'se_themeblocks';
  var $pk = 'themeblock_id';
  
  function insert($data)
  {
    $id = parent::insert($data);
    if ($id) {
      $rc_theme = new rc_theme();
      $res = $this->db->database_query("ALTER TABLE $rc_theme->table ADD ".$rc_theme->get_block_column($id)." TEXT NULL");
      if (!$res) {
        parent::delete($id);
        return false;
      }
      return true;
    }
    return false;
  }
  
  function delete($id)
  {
    $res = parent::delete($id);
    if ($res) {
      $rc_theme = new rc_theme();
      $this->db->database_query("ALTER TABLE $rc_theme->table DROP COLUMN ".$rc_theme->get_block_column($id));
    }
    return $res;
  }
  
}
