<?php
/**
    * Gather together the configs from the database
    * configuration table.
    */ 

class Configuration 
{
   function getConfigurations(){
   $config = array();
   $q = "SELECT * FROM ".TBL_CONFIGURATION;
   	  $result = mysql_query($q, $database->connection);
   	  while($row = mysql_fetch_assoc($result)) {
   	  	$config[$row['config_name']] = $row['config_value'];
   	  }
   	  return $config;
   }
   
}
/* Initialize configuration object */
$configuration = new Configuration;
?>