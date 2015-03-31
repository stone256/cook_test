<?php

namespace Stone256\CookBundle\Entity;

use Stone256\CookBundle\Entity\fridge;

class recipes
{
   /**
    * parsing error
    */
   private $error = false;
   
   /**
    * php aray
    */
   private $data;
   
    /**
     * load input
     */
    function __construct($input_string){
	$this->string = (string)$input_string;
	$this->parse();
    }
    /**
     * parse data and validate
     */
    public function parse(){
    
      $data = json_decode($this->string,true);
      //no data or not json
      if(!$data || !is_array($data) || count($data)<1 ){
	  $this->error[] = "Incorrect recipes input";
	  return;
      }
      
      foreach($data as $k=>$v){
	  if(!is_array($v)){
	      $this->error[] = "recipe # ".($k+1)." is in wrong format";
	      continue;
	  }
	  if(!trim($v['name']) ){
	      $this->error[] = "recipe # ".($k+1)." has no name " ;
	  }
	  if(!is_array($v['ingredients'])){
	      $this->error[] = "recipe # ".($k+1)." ingredients not readable" ;
	      continue;
	  }
	  foreach($v['ingredients'] as $ki=>$vi){
	    if(!is_array($vi)){
	      $this->error[] = "recipe # ".($k+1)." ingredients not readable." ;
	      continue;
	    }
	    if(!trim((string)$vi['item'])){
	      $this->error[] = "recipe # ".($k+1)." ingredients name error" ;
	    }
	    if(!(int)$vi['amount']){
	      $this->error[] = "recipe # ".($k+1)." ingredients amount error" ;
	    }
	    if(!in_array($vi['unit'], fridge::$units)){
	      $this->error[] = "recipe # ".($k+1)." ingredients amount error" ;
	    }
	  }
      }
      $this->data = $data;
      
    }
    
    
      /**
    * return parse error
    */
   public function isError(){
      return $this->error;
   }
   
   /**
    * return presed data
    */
   public function getData(){
	return $this->data;
    }
    
    /**
     * check food you have to match recipes
     *
     */
    public function match($fridge){
	$material = $fridge->getData();

	$today = Date('Y-m-d');

	$matched = array();
	foreach($this->data as $kr=>$vr){
	      //match ingredients
	      $match = true;
	      foreach($vr['ingredients'] as $ki=>$vi){
		  if(!$material[$vi['item']]){
		    $match = false;	//no ingredints
		  }
		  if($material[$vi['item']]['Use-By'] < $today){
		    $match = false;	//expired
		  }
		  if($material[$vi['item']]['Amount'] < $vi['amount'] ){
		    $match = false; 	//not enought
		  }
		  if($material[$vi['item']]['Unit'] < $vi['unit'] ){
		    $match = false; 	//wrong units
		  }
	      }
	      if($match) $matched[] = $vr['name'];
	}
 
	return count($matched) ? implode(',', $matched) : 'Order Takeout'; 
     
    }
    
}
