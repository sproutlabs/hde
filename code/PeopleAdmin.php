<?php 

class PeopleAdmin extends ModelAdmin {
   
  public static $managed_models = array(
      'Person','Relationship'
   );
  
  static $url_segment = 'People'; // will be linked as /admin/products
  static $menu_title = 'People';
 
}


?>
