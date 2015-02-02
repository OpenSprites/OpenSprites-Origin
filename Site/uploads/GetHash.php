<?php

  // Use this to get the name of hashes
  
  function GetNameForHash($hash){
    $hash_array = parse_ini_file("Hashes.txt");
    return $hash_array[$hash];
  }
?>