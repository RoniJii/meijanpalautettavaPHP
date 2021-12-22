<?php

//tietokannan aukaseminen
function openDB() {
  $db = new PDO('mysql:host=localhost;dbname=imdb;charset=utf8','root','');
  $db ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  return $db;
}
//errorien nappaaminen ja headerin asettaminen
 function returnError(PDOException $pdoex) {
  echo header('HTTP/1.1 500 Internal Server Error');
  $error = array('error' => $pdoex -> getmessage());
  print json_encode($error);
  exit;
}
