<?php

include_once "class_radcodes.php";

class rc_employment extends rc_model 
{
  var $table = 'se_employments';
  var $pk = 'employment_id';
  var $user_id;
  
  function rc_employment($uid=null)
  {
    rc_model::rc_model();
    $this->user_id = $uid;
  }
  
  function insert($data)
  {
    if ($this->user_id) $data['employment_user_id'] = $this->user_id;
    return rc_model::insert($data);
  }
  
  function get_user_criteria()
  {
    return ($this->user_id) ? "employment_user_id = '$this->user_id'" : null;
  }
  
  function join_user_criteria($criteria)
  {
    $uc = $this->get_user_criteria();
    return ($uc) ? "$criteria AND $uc" : $criteria;
  }
  
  function get_employments($condition,$key=true)
  {
    if ($condition) $condition = " AND ".$condition;
    $criteria = "WHERE ".$this->get_user_criteria()." $condition ORDER BY employment_employer ASC";
    return $this->get_records($criteria, $key);
  }
  
  function get_record_by_criteria($criteria)
  {
    return rc_model::get_record_by_criteria($this->join_user_criteria($criteria));
  }  
  
  function update_by_criteria($criteria, $data)
  {
    return rc_model::update_by_criteria($this->join_user_criteria($criteria),$data);
  }  
  
  function delete_by_criteria($criteria)
  {
    return rc_model::delete_by_criteria($this->join_user_criteria($criteria));
  }

  function build_searchable_fields($records)
  {
    global $header_employment;
    
    foreach (explode(',',SE_Language::_get(11050103)) as $letter) {
      $months[++$i] = $letter;
    }
    
    foreach ($records as $k=>$record) {
      foreach ($record as $field => $value) {
        if (strlen($value)) {
          $search_value = "search[$field]=".urlencode($value);
          if ($field == 'employment_to_month' || $field == 'employment_from_month') {
            $value = $months[$value];
          }
          elseif ($field == 'employment_is_current') $value = SE_Language::_get(11050109);
          
          $records[$k]["search_$field"] = "<a href='search_employment.php?task=browse&amp;{$search_value}'>{$value}</a>";
        }
      }
    }
    return $records;
  }
  
}
