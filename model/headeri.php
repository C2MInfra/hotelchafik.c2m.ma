<?php

   
 
 
function __autoload($class_name) {
include_once('model/'.$class_name.'.php');
}
?>
