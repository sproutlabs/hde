<?php 

class WayPoint extends Loc {
 
    
   static $db = array(
      	'Name' => 'Varchar',
        'Time' => 'Float'// A way for representation how long it might take to this spot
    );

 
  static $has_one = array(
   );
	
   static $belongs_many_many = array(
    "Vogages" => "Vogage",
  );	

  
   static $searchable_fields = array(
   'Name' );
   
  
  static $summary_fields = array(
   'Name','ID'
   );
   
  public  function getCMSFields() {

		$fields = parent::getCMSFields();
		 return $fields; 
	 
	 }
	
public function CMSFields_forPopup()
	{
		
		$fields = parent::getCMSFields(); 
		
		
		 
	        return $fields; 

	
	} 
	
};

?>
