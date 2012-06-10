<?php

include_once "class_radcodes.php";

class rc_recommendedvote extends rc_vote 
{
  var $table = 'rc_recommendedvotes';

  /*
   * *ers = user_id
   * *ees = object_id
   */
  
  function count_recommenders($object_id)
  {
    return $this->count_by_object_id($object_id);
  }
  
  function count_recommendees($user_id)
  {
    return $this->count_by_user_id($user_id);
  }
  
  function get_recommenders($object_id, $start, $limit, $sort=null)
  {
    if ($sort === null) $sort = "$this->pd DESC";
    $criteria = "WHERE $this->po = '$object_id' ORDER BY $sort LIMIT $start, $limit";
    $records = $this->FindRecordsByCriteria($criteria);
    return $records;
  }
  
  function get_recommendees($user_id, $start, $limit, $sort=null)
  {
    if ($sort === null) $sort = "$this->pd DESC";
    $criteria = "WHERE $this->pu = '$user_id' ORDER BY $sort LIMIT $start, $limit";
    
    $u = new rc_user();
    $query = "SELECT * FROM $this->table
     JOIN {$u->table} ON {$u->table}.{$u->pk} = {$this->table}.{$this->po}
     $criteria
    ";
     
    $objs = array(); 
    $res = $this->db->database_query($query);
    while($data = $this->db->database_fetch_assoc($res)) {
      $obj = new $this->_class($this);
      $obj->setProperties($data);
      
      $rc_user = new rc_user();
      $rc_user->setProperties($data);
      $obj->rc_user = $rc_user;
      
      $objs[$data[$this->pk]] = $obj;  
    }

    return $objs;    
  }
  
  function count_user_recommenders($where="")
  {
    $total = $this->count_voters($where);
    return $total;
  }
  
  // who recommends the most people (ie .. do alot of recommendation) .. who do the recommendation
  function get_user_recommenders($start=0, $limit=10, $where="", $sort="total_votes DESC")
  {
    $rc_users = $this->get_voters($start, $limit, $where, $sort);     
    return $rc_users;
  }
  
  function count_user_recommendees($where="")
  {
  	return $this->count_objecters($where);
  }
  
  // who got the most recommendation, ie who receive the recommendations.. object
  function get_user_recommendees($start=0, $limit=10, $where="", $sort="total_votes DESC")
  {   
    return $this->get_objecters($start, $limit, $where, $sort);
  }
  
}