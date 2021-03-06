<?php

class LocationsDisplayPage extends Page {

 	static $db = array(
 		'PeoplePerPage' => 'Int'
 	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->addFieldsToTab('Root.Content.PageControl',array(
			new NumericField('PeoplePerPage','People Per Page'),
		));
		return $fields;
	}	
	

}

class  LocationsDisplayPage_Controller extends Page_Controller {

	static $number_per_page = 7;
	
	
	function init() {
              parent::init();
			  
			  Requirements::javascript("http://maps.google.com/maps?file=api&v=2.x//maps.google.com/maps?file=api&v=2.x&key=ABQIAAAAHwMDoJv7ztwpHkLRxQpwXhTd4Yb1hKwwc865J-MNGv4PuLdf3hTTpz1UPuxrXLma8Kx9rUatwRZ0ow");
			   
			  //$people = DataObject::get('Person'); 
			  //Debug::show("ha");

		///return $this ->renderWith(array('PeopleDisplayPage', 'Page'));
				  
          }
	  
   
   function map() {
		 
		
		header("Content-type: text/xml");
		die($this->renderWith(array('map')));
	
		
		
    }
   
   function People() {
	   	
	 //Need to do this all the data objects where we might find the person.  
	  
		if(!isset($_GET['start']) || !is_numeric($_GET['start']) || (int)$_GET['start'] < 1) $_GET['start'] = 0;
  		$SQL_start = (int)$_GET['start'];
  
	  
	
	  $workflowObject = DataObject::get_one('WorkFlow','Name = "Publish"');
	  $workflowID = $workflowObject->ID; 
	 
		
		  $Locs = DataObject::get("Loc",
		  						   "",
								   "",
								   "LEFT JOIN Events ON Events.LocationID = Loc.ID",
									""
									 ); //Get all the blacks 

		//Debug::show($Locs);
		
		
		 $LocsSet = new DataObjectSet();
				
			
	 	//Now search to see if this has actually been used 	
	 	 foreach ($Locs as $Loc) {
	 	 	
				$LocID = $Loc->ID;


					    	
			  $LocEvents = DataObject::get(
										"Events",
										 "LocationID = {$LocID}"
										
										 );
						if(($LocEvents != Null)   ) {
							 $LocsSet->push($Loc);
						} else {
							  $Ships = DataObject::get(
										"Ship",
										 "LeavesFromID = {$LocID} or LocationID = {$LocID} "
										
								 );
								if(($Ships != Null)   ) {
								 $LocsSet->push($Loc);
							}	 
						
				      
			    };
				
			
			
					 							 
		 	}
	 	 	
		//Debug::show($LocsSet);

	 	return $this->customise(array('Locations' => $LocsSet));
	 
	 
	  
   }
   
   

}


?>
