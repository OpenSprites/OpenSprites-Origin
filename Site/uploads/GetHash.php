<?php

  // Use this to get the info of hashes
  
  function GetNameForHash($hash){
    $hash_array = parse_ini_file(dirname(__FILE__)."/Hashes.txt");
    return $hash_array[$hash];
  }
  function GetUserForHash($hash){
    $hash_array = parse_ini_file(dirname(__FILE__)."/User.txt");
    return $hash_array[$hash];
  }
  function GetVersionForHash($hash){
    $hash_array = parse_ini_file(dirname(__FILE__)."/Version.txt");
    return $hash_array[$hash];
  }
  function GetTypeForHash($hash){
    $hash_array = parse_ini_file(dirname(__FILE__)."/Type.txt");
    return $hash_array[$hash];
  }
  
  //
  
  function InsertPairIntoFile($key, $value, $file_name){
    $hash_file = file_get_contents(dirname(__FILE__)."/".$file_name.".txt");
    $hash_file .= "\n".$key.' = '.$value.'';
    file_put_contents(dirname(__FILE__)."/".$file_name.".txt", $hash_file);
  }
?>