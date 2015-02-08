<?php

  // Use this to get hashes of passwords
  
  function GetHashForName($name){
    $hash_array = parse_ini_file(dirname(__FILE__)."/Passwords.txt");
    return($hash_array[$name]);
  }
  
  //
  
  function AddHashNamePair($key, $value){
    $hash_file = file_get_contents(dirname(__FILE__)."/Passwords.txt");
    $hash_file .= "\n".$key.' = '.$value.'';
    file_put_contents(dirname(__FILE__)."/Passwords.txt", $hash_file);
  }
  
  function ReplaceNameHash($key, $value){
    $file_pointer = fopen(dirname(__FILE__)."./Passwords.txt", 'r+');
    while(!feof($file_pointer)){
      $single_line = fgets($file_handle);
      $single_line_array = explode('=', $single_line);
      if($single_line_array[0] === $key){
        fseek($file_pointer, 0 - strlen($single_line));
        fseek($file_pointer, strlen($key) + 1);
        fwrite($file_pointer, $value);
        continue;
      }
    }
    fclose($file_pointer);
  }
?>