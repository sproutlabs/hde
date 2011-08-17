<?php 


class Person extends DataObject {
 
 	static $api_access = true;
   function canView() { return true; }
   
   
   static $db = array(
      'Name' => 'Varchar',
	  'Remarks' => 'HTMLText',
	  'DateOfBirth' => 'Int',
	  'Featured' => 'Boolean'
	);

	static $has_one = array(
	 'Visual' => 'Visual',
      'Gender' => 'Gender',
	  'CustomImage' => 'Image', 
	  'Status' => 'WorkFlow',



   );
	 
	 static $has_many = array(
		'EventRelationship' => 'EventRelationship',


	);
	 
	 static $many_many = array(
		'InformationPage' => 'InformationPage',

		'EventData' => 'EventData',			

    );


	   
	
	  static $belongs_many_many = array(
		
								
		'Relationships' => 'Relationship',

		//'Events' => 'Events',
		'myGroups' => 'myGroups',
  		
		
		
		);


		 
	
  
   static $searchable_fields = array(
       'Name','ID','Visual.Name',
   );
   
  
    static $summary_fields = array(
     'Name','ID','Visual.Name',
   );
	
	public function GetBON() {
		//Debug::show($this->ID);
		$where = Convert::raw2SQL($this->ID);
		$BON = DataObject::get('BONData',"KnownAsID = $where");
		return $BON;
	}
	
	public function GetLocation() {
		//Debug::show($this->ID);
		$where = Convert::raw2SQL($this->ID);
		
		$BON = DataObject::get_one('BONData',
									"KnownAsID = $where",
									"",
							    	"" );
		$theID = $BON->ID; 
			
		//Debug::show($BON->Owner->ID);
			
		/*$BONOwnerRecord = DataObject::get('BONData_Owner',
									"BONDataID = $theID",
									"",
							    	"" );
		Debug::show($BONOwnerRecord);
							
		$Owner = DataObject::get_one('Owner',
									"BONDataID = $BONOwnerRecord->OwnerID",
									"",
							    	"" );
									
		Debug::show($Owner);
		
		$ranLocation =  $BON->Owner; 
		Debug::show($BON->Owner);
		*/
		return $ranLocation;
	}
	
	
	
	public function GetAge() {
		//Debug::show($this->ID);
		$where = Convert::raw2SQL($this->ID);
		$BON = DataObject::get_one('BONData',"KnownAsID = $where");
		
		
		if ($BON != null) {
				$age = $BON->Age ; 

			if(intval($age) - $age == 0) {
				$age = $BON->Age ; 
			} else {
				//The number must be some like lik 1.5 so turn that in months.
				$age = $BON->Age ; 
 			    
				//Debug::show("not a whole number");
  			    //Debug::show($age);


				$age = round($BON->Age * 12) ; 
				$age = $age . " months";
				  			    //Debug::show($age);

			}
		} else {
			$age = NULL;
		}
		//Debug::show($bornYear);
	
		return $age;
	}
	
	
	
	
	public function GetBorn() {
		//Debug::show($this->ID);
		$where = Convert::raw2SQL($this->ID);
		$BON = DataObject::get_one('BONData',"KnownAsID = $where");
		
		//Debug::show($BON->Age);
		$bornYear =  round(1783 - $BON->Age); 
		//Debug::show($bornYear);
	
		return $bornYear;
	}
	
	public function GetRan() {
		//Debug::show($this->ID);
		$where = Convert::raw2SQL($this->ID);
		$BON = DataObject::get_one('BONData',"KnownAsID = $where");
		
		//Debug::show($BON->Age);
		if($BON){
			$ranYear =  1783 - $BON->TimeLeft;
		} else {
			return;
		}
		//Debug::show($bornYear);
	
		return $ranYear;
	}
	
		
public function getCustomSearchContext() {
		$fields = $this->scaffoldSearchFields(array(
			'restrictFields' => array('Name')
		));
		$filters = array(
			'Name' => new PartialMatchFilter('Name'),
			//'Transcript' => new PartialMatchFilter('Transcript'),
		);
		return new SearchContext(
			$this->class, 
			$fields, 
			$filters
		);
	}
	
	
	
	
	   function getCMSFields() {
		   
			

			 $fields = parent::getCMSFields(); 
			  
			  	
		 	$labelText = '<h2><a href="/person/display/' .  $this->ID . '" target="new" >Link to front end page</a></h2>';
			
		 	$label = new LiteralField ( $title = "Link",
									   $content = $labelText
			   );
			
			$fields->addFieldToTab("Root.Main",$label);
				
				
				
			 $fields->replaceField ('Remarks', $wysiwyg = new TextareaField('Remarks','',$rows = 8));	


			 $fields->removeByName('my Groups'); //Removes the My Groups from the People area.
			  $fields->removeByName('Events'); //Removes the My Groups from the People area. 
			  $fields->removeByName('EventData');
		       $fields->removeByName('Relationships'); //Removes the My Groups from the People area. 
			 
		     $fields->removeByName('EventRelationship'); //Removes the My Groups from the People area. 

 		
		$fields->removeByName('Information Page');

			$infoOnTablefield = new ManyManyDataObjectManager(
				 $this,
				'InformationPage',
				'InformationPage',
				 array(
					'Summary' => 'Summary'
				 )
			  );
			
				
		   $fields->addFieldToTab("Root.Info", $infoOnTablefield);
			
			
			

				 return $fields;
  	   }
 
 	public function CMSFields_forPopup()
	{
		
	
	 $fields = parent::getCMSFields(); 
			 $fields->removeByName('my Groups'); //Removes the My Groups from the People area.
			  $fields->removeByName('Events'); //Removes the My Groups from the People area. 
			  			  $fields->removeByName('EventData');

		       $fields->removeByName('Relationships'); //Removes the My Groups from the People area. 
			 
			 		       $fields->removeByName('DateOfBirth'); //Removes the My Groups from the People area. 


		$fields->removeByName('Status');
		$fields->removeByName('Featured');
//						$fields->removeByName('Visual');

		$fields->removeByName('Custom Image');


	
					$this->StatusID = 1; 




		
		return $fields;
		
	}
	
 public function populateDefaults(){
	 
    	parent::populateDefaults(); 		
		 
		$this->VisualID = 2; //TODO - get this what from being hardwired  
		$this->StatusID = 1;
				
			
	}
	
	
 
   
};

?>
