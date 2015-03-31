<?php

namespace Stone256\CookBundle\Entity;


/**
 * fridge
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Stone256\CookBundle\Entity\fridgeRepository")
 */
class fridge
{
   
   /**
    * datafile path
    */
   private $filepath;
   
   /**
    * parsing error
    */
   private $error = false;
   
   /**
    * php aray
    */
   private $data;
   /**
    * csv column
    */
   private $columns =array('Item', 'Amount', 'Unit', 'Use-By');
   
   /**
    *  units
    */
   static $units = array('of', 'grams', 'ml', 'slices');
   
   /**
    * create data
    */
   public function __construct($filepath){
	$this->filepath = $filepath;
	$this->parse();
   }
   
   /**
    * parse CSV to php array: validation
    */
   public function parse(){
   
	if(!trim($this->filepath)){
	  $this->error[] = "please select a csv file";
	  return ;
	}
	//check file type
	if(!preg_match('/\.(csv|CSV)$/', $this->filepath)){
	  $this->error[] = "wrong file type";
	  return ;
	}
	//check if empty
	if(!($data = $this->gets($this->filepath)) || count($data)<1) {
	  $this->error[] = 'Incorrect format';
	  return ;
	}
	$brr = array();
	//check and convert data
	foreach($data as $k=>$v){
	      if(!$v[$this->columns[0]]){
		  $this->error[] = "item ".($k+1). " has no label";
	      }
	      if(!(int)$v[$this->columns[1]]){
		  $this->error[] = "item ".($k+1). " has no qty";
	      }
	      if(!in_array($v[$this->columns[2]], self::$units)){
		  $this->error[] = "item ".($k+1). " unit unknow";
	      }
	      
	      if(! preg_match('/\d\d?\/\d\d?\/\d\d\d\d/', $v[$this->columns[3]])){
		  $this->error[] = "item ".($k+1). " wrong use by date format";
	      }
	            

	      $v[$this->columns[3]] = date('Y-m-d', strtotime(str_replace('/','-', $v[$this->columns[3]])));
	      $brr[$v[$this->columns[0]]] = $v;
	}
	  
	$this->data = $brr;
	
	return;
	
	
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
      * get array form cvs file
      *
      * @param mix $handle		: opened file handle or file-path
      * @param boolean $title	: true if first row is column title
      * @param int $length		: max row length , 0 = unlimited.
      * @param  string/char 	$delimiter
      * @param string/char $enclosure
      * @return unknown
      */
    function gets($handle,$title=true,$length=0,$delimiter=',',$enclosure = '"'){
	    
	if(is_string($handle)) $handle = fopen($handle,'r');
	while (($rows[] = fgetcsv($handle, $length, $delimiter,$enclosure))) 1;
	array_pop($rows);
	if($title){
	    reset($rows);
	    $title=array_shift($rows);
	    //$title = xpAS::trim($title);
	    
	    foreach ($rows as $k=>$v)
		    foreach ($title as $kt=>$vt)
			    $brr[$k][$vt] = $v[$kt];
	    $rows = $brr;		
	}
	return $rows;
    }
   
}