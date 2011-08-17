<?php

class PersonDisplayPage extends Page {


}

class  PersonDisplayPage_Controller extends Page_Controller {

	function init() {
				
				parent::init();

		//Requirements::javascript("hde/javascript/qtip/jquery-1.3.2.min.js");
		
		//Requirements::javascript("hde/javascript/qtip/jquery.qtip-1.0.0-rc3.min.js");
		
                Requirements::javascript("hde/javascript/exhibit/api/exhibit-api.js?autoCreate=false");
                 Requirements::javascript("hde/javascript/exhibit/api/extensions/time/time-extension.js");
	 
//	Requirements::javascript("hde/javascript/exhibit/api/extensions/map/map-extension.js?gmapkey=ABQIAAAAHwMDoJv7ztwpHkLRxQpwXhTd4Yb1hKwwc865J-MNGv4PuLdf3hTTpz1UPuxrXLma8Kx9rUatwRZ0ow");
				
                Requirements::css("themes/blackloyalist/css/SpryTabbedPanels.css");
	
	
				
				

          }
		
	function toJSON() {
		
					   
			$params = Director::urlParams();
			 $id = (int)$params['ID'];
		
			if ($id == null){
				Director::redirect('error/');
			};
				
			//-- old Silverstrip  stuff--//		
			/*$person = DataObject::get_by_id('Person',$id); 
		
			$f = new JSONDataFormatter();
			$f->relationDepth = 2;
			$json = $f->convertDataObject($person);
			return $json; */
			
			$PersonEventObjects = DataObject::get("EventData",
												"KnownAsID = '$id'",
												"Events.YEAR ASC",
												"LEFT JOIN Events ON Events.ID = EventData.EventID");
			
			//header("Content-type: application/json");	

			return $this->customise(
			array('EventData' => $PersonEventObjects))
			->renderWith('PersonJson');
				  
				  

	}
	
	function buildDynamicRanEvents($PersonEventObjects) {
		
		//$DynamicEventObjects = DataObject::get("Events","RuleBased = 1 ");
			//Debug::show($PersonEventObjects);

			$BONData = $PersonEventObjects->find('ClassName', 'BONData');
			
			if(isset($BONData->TimeLeft)) { 
						$ranYear =  1783 - $BONData->TimeLeft;
							
						  // Debug::Show($ranYear);
				
							$DynamicEventObjects = DataObject::get("Events","RuleBased = 1 AND RuleRanStart <= $ranYear AND RuleRanEnd >= $ranYear");
						
							return  $DynamicEventObjects;
			}
		
	}	
	/* Builds a dynamic event that is show the birth date of a run away*/	
		
	function buildBirth($PersonEventObjects) {
		
			//Debug::show($PersonEventObjects);
			$BONData = $PersonEventObjects->find('ClassName', 'BONData');
			
				if(isset($BONData->Age)) { 
						//Debug::Show("yes we have a year"); 
				
				$bornYear =  1783 - $BONData->Age; 
			//	Debug::show($bornYear);
				
				/* old stuff for when the birth data was being made on the run $myBirthEvent = new Events();	
				$myBirthEvent->Name = "Born";
				$myBirthEvent->Year =  $bornYear;
				$myBirthEvent->ID =  10000;
				//Debug::show($myBirthEvent); */
	
				//Now link that to EventData 
			
				$BornEvent = DataObject::get_one("Events","Name = 'Born'" ); 
				
				$BornEvent->Year = $bornYear;
				
				//Debug::Show($BornEvent);
	
				//$BornEvent->write();
				
				$myBirthDataSet = new DataObjectSet();
				
				$myBirthDataEvent = new CustomEvent();
				
				$myBirthDataEvent->EventID = $BornEvent->ID;
				//Debug::Show($BONData->KnownAsID);
				$myBirthDataEvent->KnownAsID = $BONData->KnownAsID;
	
	
				
				//Debug::show($myBirthDataEvent);
				$myBirthDataSet->push($myBirthDataEvent);
				return $myBirthDataSet;
				  }

		}
	

function buildRan($PersonEventObjects) {
		
			//Debug::show($PersonEventObjects);
			$BONData = $PersonEventObjects->find('ClassName', 'BONData');
			//Debug::show($BONData->Age);
			
			if(isset($BONData->TimeLeft)) { 
				
				if ($BONData->TimeLeft != 0 ) {
					$ranYear =  1783 - $BONData->TimeLeft;
		
					$BornRan = DataObject::get_one("Events","Name = 'Ran'" ); 
					$BornRan->Year = $ranYear;   
					
					//Debug::Show($BornRan);
								
					$myRanDataSet = new DataObjectSet();
					
					$myRanDataEvent = new CustomEvent();
					
					$myRanDataEvent->EventID = $BornRan->ID;
					
					//Debug::Show($BONData->KnownAsID);
					$myRanDataEvent->KnownAsID = $BONData->KnownAsID;
		
		
						//Debug::show($myBirthDataEvent);
					$myRanDataSet->push($myRanDataEvent );
					return $myRanDataSet ;	
				}
			
			}
		}

function buildOwnerRan($id) {
			
			$BONData = DataObject::get(
							"BONData",
							 "BONData_Owner.OwnerID = {$id}",
							 "Events.YEAR ASC",
							 "LEFT JOIN BONData_Owner ON BONData.ID = BONData_Owner.BONDataID 
							  LEFT JOIN Events ON Events.ID = EventData.EventID ",
							 ""
							 );
						
			//$BONData  = $BONData->GroupedBy('TimeLeft');
			
			//Debug::show($BONData);
			$RanDates = array(); 
			
			$myRanDataSet = new DataObjectSet();
			$BornRan = DataObject::get_one("Events","Name = 'Ran'" ); 


			foreach ($BONData  as $BON){
				if(!in_array($BON->TimeLeft,$RanDates)) {	
					array_push($RanDates,$BON->TimeLeft);
					//Debug::Show($RanDates);		
					
					$RanYear =   1783 - $BON->TimeLeft;
					$BornRan->Year = $RanYear; 
					//Debug::Show($BornRan);		

					$myRanDataEvent = new RanEvent();
					$myRanDataEvent->EventID = $BornRan->ID;
					$myRanDataEvent->RanYear = $RanYear;


					$myRanDataEvent->KnownAsID = $id;

					$myRanDataSet->push($myRanDataEvent );


				}
			}
		
		
		
			
			//Debug::Show($BornRan);
			
					
			
			
			//Debug::Show($myRanDataEvent);
			
			return $myRanDataSet ;
		
			
		}
		
		
   function display() {
		
      $params = Director::urlParams();
      $id = (int)$params['ID'];

	 if ($id == null){
		//Director::redirect('error/');
		}//

	 //Need to do this all the data objects where we might find the person.

	Session::set('CurrentPerson', $id);


	$person = DataObject::get_by_id('Person',$id);
	//Debug::show($person);

    if($person == null) {
      //  Director::redirect('error/')
    };


	$this->Title = $person->Name;//Make the person name show up in the title
				$eventObjects = new DataObjectSet();


				//Only do this stuff if user is logged.

		//	Debug::show();
	  		$personVisual =  $person->VisualID;
			$currentVisual = DataObject::get_by_id('Visual',$personVisual);



			//----------SEARCH THRU ALL THE BITS OF DATA this is bit messy for the white people  ----------- it would be good this was more elegeant

			// --- these just get one entry so that in the templates Getter that list all the owners/sponsors can actually be made


	       if ($currentVisual->Name == "White" ) {



						$RawTithablesObjects = DataObject::get("TithablesData",
										"OwnerID = {$id}",
										"Events.YEAR ASC",
										"LEFT JOIN Events ON Events.ID = EventData.EventID",
										"");


						$foundTithalbeEvents = array();
						//$foundTithalbeEvents->push(0);
						$TithablesObjects = new DataObjectSet();



						if($RawTithablesObjects) {

							 foreach ($RawTithablesObjects as $Tithables) {


									   //Debug::show($Tithables);
									  // Debug::show(in_array( $Tithables->EventID,	$foundTithalbeEvents));

									 	$found =  in_array($Tithables->EventID,$foundTithalbeEvents);

											if($found == 1 ) {

												//Debug::show("This event has happend before");

											} else {

												//Debug::show("This event hasn't happend before");

												$TithablesObjects->push($Tithables);
 												array_push($foundTithalbeEvents,$Tithables->EventID);
											}


							 }
						}


						$eventObjects->merge($TithablesObjects);


						$BONData = DataObject::get(
							"BONData",
							 "BONData_Owner.OwnerID = {$id}",
							 "Events.YEAR ASC",
							 "LEFT JOIN BONData_Owner ON BONData.ID = BONData_Owner.BONDataID
							  LEFT JOIN Events ON Events.ID = EventData.EventID ",
							 "1"
							 );


						if($BONData) {
								$ranEvent = $this->buildOwnerRan($id);
								//Debug::Show($BONData);
			            		$eventObjects->merge($ranEvent);

								//Debug::Show($eventObjects);

						}



						$eventObjects->merge($BONData);


						$BONSponsor = DataObject::get("BONData",
							"BONData_Sponsor.PersonID = {$id}",
							"Events.YEAR ASC",
							 "LEFT JOIN BONData_Sponsor ON BONData.ID = BONData_Sponsor.BONDataID
							  LEFT JOIN Events ON Events.ID = EventData.EventID",
							  "1"

							 );

						$eventObjects->merge($BONSponsor);

						//$BirchtownMusterObjects = DataObject::get("BirchtownMusterData","OwnerID = {$id}");

						$BirchtownMusterObjects = DataObject::get(
								"BirchtownMusterData",
								"BirchtownMusterData_Owner.OwnerID = {$id}",
								"Events.YEAR ASC",
								"LEFT JOIN BirchtownMusterData_Owner ON BirchtownMusterData.ID = BirchtownMusterData_Owner.BirchtownMusterDataID
								 LEFT JOIN Events ON Events.ID = EventData.EventID ",
								 "1"
							 );

						$eventObjects->merge($BirchtownMusterObjects);



		   }

		  $PersonEventObjects = new  DataObjectSet();
		//just the stanard searches
		  $PersonEventObjects = DataObject::get("EventData",
												"KnownAsID = '$id'",
												"Events.YEAR ASC",
												"LEFT JOIN Events ON Events.ID = EventData.EventID");


		//Debug::Show($PersonEventObjects);
			//Debug::Show($currentVisual->Name);

		//Now we need build the birthdate but we only do that for the run aways
		 if (($currentVisual->Name == "Black") || ($currentVisual->Name == "Mulatto")  ) {

			$birthEvent = $this->buildBirth($PersonEventObjects);
			//Debug::Show($birthEvent);
			$eventObjects->merge($birthEvent);

			 }

		//really need try and merge the data objects together




		//Now we need build the ran data but we only do that for the run aways
		 if (($currentVisual->Name == "Black") || ($currentVisual->Name == "Mulatto")  ) {

			$ranEvent = $this->buildRan($PersonEventObjects);
			//Debug::Show($ranEvent);
			$eventObjects->merge($ranEvent);

			 }


			$eventObjects->merge($PersonEventObjects);  //got them together
			//Debug::Show($eventObjects);



		//$eventObjects->merge($ownerObjects);

	  $ClaimsEventObjects = new  DataObjectSet();
		//just the stanard searches
		  $ClaimsEventObjects = DataObject::get("LoyalistClaims",
												"LoyalistClaims_RunAways.PersonID = '$id'",
												"",
												"LEFT JOIN LoyalistClaims_RunAways ON LoyalistClaims.ID =  LoyalistClaims_RunAways.LoyalistClaimsID");
		//really need try and merge the data objects together



		$eventObjects->merge($ClaimsEventObjects);  //got them together
		//$eventObjects->merge($ownerObjects);
		//$eventObjects->sort('EventID.Year', 'DESC'); /// BUG when we add this it all stops working.



	  //Now send this back the browser.

	  //Now we get the other dynamic events

	  //Get the ones for that are for everyone

	  $DynamicEventObjectSet = new DataObjectSet();

			//Debug::show($myBirthDataEvent);

		//This is the event for everyone,
	   $DynamicEventObjects = DataObject::get("Events","RuleBased = 1 AND RuleIsEveryone = 1 ");
	  // Debug::Show($DynamicEventObjects);


	  if($DynamicEventObjects) {
	  	 foreach($DynamicEventObjects as $Event) {
	  	 	$dynamicEvent = new CustomEvent();
			$dynamicEvent->EventID = $Event->ID;
			$eventObjects->push($dynamicEvent);

	  	 	//Debug::Show($Event);
	  	 }
		 //$eventObjects->merge($DynamicEventObjects);
	  }


	 $DynamicRanEvents =  $this->buildDynamicRanEvents( $eventObjects);
	 //Debug::show( $DynamicRanEvents);

	if( $DynamicRanEvents) {
	  	 foreach( $DynamicRanEvents as $Event) {
	  	 	$dynamicEvent = new CustomEvent();
			$dynamicEvent->EventID = $Event->ID;
			$eventObjects->push($dynamicEvent);

	  	 	//Debug::Show($Event);
	  	 }

	 	//$eventObjects->merge($DynamicRanEvents);  //got them together
	  }







	  // Now we start in the


		//Debug::Show($eventObjects);

		$eventObjects->sort('Year');//ascending

		foreach($eventObjects as $EventData) {

					//Debug::Show($EventData->EventID);

				 // Debug::Show($EventData->Year);
		}

			$relationshipsObjects = DataObject::get(
								"Relationship",
								"Relationship_Relatives.PersonID = {$id}",
								"",
								"LEFT JOIN Relationship_Relatives ON Relationship.ID = Relationship_Relatives.RelationshipID
							 ",
								 ""
							 );

	//Debug::show($relationshipsObjects);


	if(Permission::check(0)){//Check to see if vieerw is login

	   if ($person->ClassName == "Owner" ) {

			return $this
					  ->customise(array('Person' => $person, 'EventData' => $eventObjects, 'TithablesData' => $TithablesObjects))
					  ->renderWith(array('OwnerDisplayPage', 'Page'));
	   } else {

			  //Debug::show($eventObjects);

				return $this
				  ->customise(array('Person' => $person,'EventData' => $eventObjects))
				  ->renderWith(array('PersonDisplayPage', 'Page'));
	   }

   	} else {
      //This is what happen if the user is not logged in
	  //Debug::show("Not logged"'

		$CurrentStatus = $person->Status();// Get the status

			//Debug::show($CurrentStatus);

			  if($CurrentStatus->Name == 'Publish')
				{

			//Debug::show("Show  it the world");

		 if ($person->ClassName == "Owner" ) {
					return $this
							  ->customise(array('Person' => $person,'EventData' => $eventObjects))
							  ->renderWith(array('OwnerDisplayPage', 'Page'));
			   } else {
						return $this
						  ->customise(array('Person' => $person,'EventData' => $eventObjects))
						  ->renderWith(array('PersonDisplayPage', 'Page'));
			   }

				} else  {

				Director::redirect('error/');

				}

      };



  
	  
	  
   }
   
  
 

}


?>
