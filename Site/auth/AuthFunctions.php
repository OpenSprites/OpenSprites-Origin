<?php

  // Use this to get hashes of passwords
  
  function GetHashForName($name){
    $hash_array = parse_ini_file(dirname(__FILE__)."/Hashes.txt");
    return $hash_array[$name];
  }
  
  //
  
  function SetNameToHash($key, $value){
    $hash_file = file_get_contents(dirname(__FILE__)."/Passwords.txt");
    $hash_file .= "\n".$key.' = '.$value.'';
    file_put_contents(dirname(__FILE__)."/Passwords.txt", $hash_file);
  }
?>