<?php 

class Owner extends Person {
 
   static $db = array(

	);

	static $has_one = array(
	 	  'Location' => 'Loc', 
		 //'InformationPage' => 'InformationPage' 


   );
	 

	static $belongs_many_many = array(
											
			'Relationships' => 'Relationship',
	
	
			//'Events' => 'Events',
			'myGroups' => 'myGroups',
			
			
			
			);



	 
	    static $searchable_fields = array(
       'Name','ID', 'LocationID'
   );
   
  
    static $summary_fields = array(
     'Name','ID','LocationID'
   );
	

public	function GetBONOwned(){
	
	$Owned = DataObject::get(
				"BONData",
				 "BONData_Owner.OwnerID = {$this->ID}",
			     "",
			    "LEFT JOIN BONData_Owner ON BONData.ID = BONData_Owner.BONDataID"
			 );
	
	return $Owned; 
	
	
	}		
		

	
	

	
	 public	 function getCMSFields() {

		 $fields = parent::getCMSFields();

	
			
			
	$fields->removeByName('Location'); //Removes the My Groups from the People area.

		 $locs= DataObject::get('Loc');
		 $locs->sort("Name", "ASC");
		 $map = $locs->toDropDownMap('ID', 'Name');
		 $dropdownfield = new DropdownField("LocationID", "Location", $map);
		 $fields-> addFieldToTab('Root', $dropdownfield,'Name' );
		 
		/* $locs= DataObject::get('Loc');
		 $locs->sort("Name", "ASC");
		 $map = $locs->toDropDownMap('ID', 'Name');
		 $dropdownfield = new DropdownField("LocationID", "Location", $map);
		 $fields-> addFieldToTab('Root.Main', $dropdownfield, 'InformationPageID' );*/
		 
		 
		 return $fields; 
	 
	 }
	 
	 
 public function populateDefaults(){
	 
    	parent::populateDefaults(); 		
		 
		$this->VisualID = 1; //TODO - get this what from being hardwired  
				
			
	}
	
public function CMSFields_forPopup()
	{
		
		$fields = parent::getCMSFields(); 
		
		$fields->removeByName('Location'); //Removes the My Groups from the People area.

		 $locs= DataObject::get('Loc');
		 $locs->sort("Name", "ASC");
		 $map = $locs->toDropDownMap('ID', 'Name');
		 $dropdownfield = new DropdownField("LocationID", "Location", $map);
		 $fields-> addFieldToTab('Root.Main', $dropdownfield,'Name' );
		 
		 		 return $fields; 

	
	} 
	

   
};

?>
