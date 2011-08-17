<?php

class HomePage extends Page {
static $db = array (
		'ContentBelow' => 'HTMLText',
	
			
	);

		  public function getCMSFields()
			{
						$f = parent::getCMSFields();

			 $f->addFieldToTab('Root.Content.Main', new HtmlEditorField( $name = 'ContentBelow', $title='Content that goes below featured person','Content'));
				
		  
				return $f;
			}
			
	
}

class  HomePage_Controller extends Page_Controller {

	function init() {
              parent::init();
			  

				  
          }
	  
	  
	  		   
   function FeaturedPerson() {
	   	
	 //Need to do this all the data objects where we might find the person.  
	  
	    $colorObject = DataObject::get_one('Visual','Name = "White"');
	    $colorID =   $colorObject->ID; 
	
			
		$FeaturedPerson = DataObject::get_one("Person",
									  "VisualID != '{$colorID }' AND Featured=TRUE",
									  //"SUBSTRING_INDEX(Name, ' ', -1) ",//Sorts by last name
									  "",
									  "RAND()",
									  "");  
		
		 // $FeaturedPerson = DataObject::get_by_id("Person", 1042);
			/*$FeaturedPerson = DataObject::get_one("",
									  "",
									  "",
									  "",
									  "");  */
			
			
		// Debug::show($FeaturedPerson);


	 
	 	return $FeaturedPerson;
	 
	 
	  
   }
   
   
   function FormAction($data){
		
			//Debug::show("is the do Search");
				
				if (isset($data['Person']) && $data['Person'] != "") {
		 
					$Person = Convert::raw2sql($data['Person']);
		 
					Session::set("PersonSearch.Person", $Person);
				}
		 
				
				Director::redirect($this->BaseHref() . 'search');
	}
	
	function PeopleSearchForm() {
		$request = $this->getRequest();
		
		if($Person = $request->requestVar('Person')) {
			Session::set("PersonSearch.Person", $Person);
		}
		else {
			//$Person = Session::get("PersonSearch.Person");
		}
		
 
		$fields = new FieldSet();
		$fields->push(new TextField('Person','Search for a runaway',$Person));
 
		$actions = new FieldSet(
			new FormAction('FormAction','Search')
		);
 
 		return new Form($this,'FormAction', $fields, $actions);


		//return new Form($this, 'PersonSearchForm', $fields, $actions);
	}
 
 function Events() {
	   	
	 //Need to do this all the data objects where we might find the person.  
	  
		if(!isset($_GET['start']) || !is_numeric($_GET['start']) || (int)$_GET['start'] < 1) $_GET['start'] = 0;
  		$SQL_start = (int)$_GET['start'];
  		
		///Debug::show($ID_SQL);

		$events = DataObject::get("Events",
											  "RuleFeatured = 1 ",// Only show the featured Events
											  //"SUBSTRING_INDEX(Name, ' ', -1) ",//Sorts by last name
											  "Year ASC",
											  "",
											  "{$SQL_start},100"); //Get all the blacks 
	
		$events->sort('Year'); //ascending
	 	//Debug::Show($events);
		
	 	return $this->customise(array('Events' => $events));
	 
	 
	  
   }
 
 
   

}


?>