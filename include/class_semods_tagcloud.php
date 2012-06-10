<?php

class semods_tagcloud {
  
  var $tags = array();
  
  function semods_tagcloud( $tags = null) {
    if($tags) {
      if(!is_array($tags))
        $tags = explode(',', $tags);
      foreach($tags as $tag) {
        $this->add_tag( $tag );
      }
    }
  }
  
  function add_tag( $tag, $weight = 1) {
    $tag = trim(strtolower( $tag ));
    if(empty($tag))
      return;
    if(array_key_exists($tag, $this->tags)) 
      $this->tags[$tag] += $weight;
    else
      $this->tags[$tag] = $weight;
  }
  
  function shuffle() {
    $keys = array_keys($this->tags);
     
    shuffle($keys);
     
    if (count($keys) && is_array($keys))
    {
        $tmp = $this->tags;
        $this->tags = array();
        foreach ($keys as $key => $value)
            $this->tags[$value] = $tmp[$value];
    }
  }
  
  function cloud_size() {
    return array_sum( $this->tags );
  }
  
  function get_class_from_percent($percent)
  {
      if ($percent >= 99)
          $class = 1;
      else if ($percent >= 70)
          $class = 2;
      else if ($percent >= 60)
          $class = 3;
      else if ($percent >= 50)
          $class = 4;
      else if ($percent >= 40)
          $class = 5;
      else if ($percent >= 30)
          $class = 6;
      else if ($percent >= 20)
          $class = 7;
      else if ($percent >= 10)
          $class = 8;
      else if ($percent >= 5)
          $class = 9;
      else
          $class = 0;
       
      return $class;
  }
  
  
  function compile($result_type = 'html', $html_template = '') {
    $this->shuffle();
    $this->max = max($this->tags);
    if (is_array($this->tags)) {
        $result = ($result_type == "html" ? "" : ($result_type == "array" ? array() : ""));
        foreach ($this->tags as $tag => $weight) {
            $size_range = $this->get_class_from_percent(($weight / $this->max) * 100);
            if ($result_type == "array") {
                $result[$tag]['tag'] = $tag;
                $result[$tag]['size_range'] = $size_range;
            }
            else if ($result_type == "html")
            {
//                $result .= "<span class='tag size{$size_range}'> &nbsp; {$tag} &nbsp; </span>";
//                $result .= "<a class='size{$size_range}' href='user_points_shop.php?tag=$tag'>{$tag}</a>";
                //$result .= "<span class='tag size{$size_range}'>&nbsp; <a href='user_points_shop.php?tag=$tag'>{$tag}</a>&nbsp;</span>";
                $result .= str_replace( '[tag]' , $tag, $html_template );
                
            }
        }
        return $result;
    }
  }

  function to_html($link) {
    $this->shuffle();
    $this->max = max($this->tags);
    if (is_array($this->tags)) {
        $result = '';
        foreach ($this->tags as $tag => $weight) {
            $size_range = $this->get_class_from_percent(($weight / $this->max) * 100);
            $result .= "<span class='tag size{$size_range}'>&nbsp; <a href='$link?tag=$tag'>{$tag}</a>&nbsp;</span>";
        }
        return $result;
    }
  }

  
}
?>